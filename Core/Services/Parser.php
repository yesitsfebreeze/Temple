<?php

namespace Caramel\Services;


use Caramel\Models\Dom;
use Caramel\Models\Node;
use Caramel\Models\Plugin;


/**
 * Class Parser
 *
 * @package Caramel
 */
class Parser
{


    /** @var Config $config */
    private $config;


    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }


    /** @var Cache $cache */
    private $cache;


    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @param Dom $dom
     * @return bool
     */
    public function parse($dom)
    {


        $this->cache->save($dom->get("template.file"), "");
        if ($this->check($dom)) return false;

        # the returns make sure that the parse process
        # stops if we have an empty dom
        # this enables you to parse a modified dom in a plugin or function
        $dom = $this->preProcessPlugins($dom);
        if ($this->check($dom)) return false;

        $dom = $this->processPlugins($dom);
        if ($this->check($dom)) return false;

        $dom = $this->postProcessPlugins($dom);
        if ($this->check($dom)) return false;

        # parse and save the output
        $output = $this->output($dom);
        if (!$output) return false;

        $output = $this->processOutputPlugins($output);
        $this->cache->save($dom->get("template.file"), $output);

        return $output;
    }


    /**
     * checks if we have a valid dom object
     *
     * @param Dom $dom
     * @return bool
     */
    private function check($dom)
    {
        if ($dom->has("nodes")) {
            return empty($dom->get("nodes"));
        } else {
            return true;
        }
    }


    /**
     * merges the nodes to a string
     *
     * @param Dom|mixed $dom
     * @return string
     */
    private function output($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $dom->get("nodes");
        foreach ($nodes as $node) {
            /** @var Node $node */

            # open the tag
            if ($node->get("tag.display")) {
                if ($node->get("display") && $node->get("tag.opening.display")) {
                    $output .= $node->get("tag.opening.prefix");
                    $output .= $node->get("tag.opening.tag");
                    if ($node->get("tag.opening.tag") != "") {
                        $output .= " ";
                    }
                    $output .= $node->get("attributes");
                    $output .= $node->get("tag.opening.postfix");
                }
            }

            if ($node->has("content")) {
                $content = $node->get("content");
                if (gettype($content) == "string") {
                    $output .= $content;
                }
            }

            # recursively iterate over the children
            if ($node->has("children")) {
                if (!$node->get("selfclosing")) {
                    $children = new Dom();
                    $children->set("nodes", $node->get("children"));
                    $output .= $this->output($children);
                } else {
                    new Error("You can't have children in an " . $node->get("tag.tag") . "!", $node->get("file"), $node->get("line"));
                }
            }

            # close the tag
            if ($node->get("tag.display")) {
                if ($node->get("display") && $node->get("tag.closing.display") && $node->get("tag.display")) {
                    if (!$node->get("selfclosing")) {
                        $output .= $node->get("tag.closing.prefix");
                        $output .= $node->get("tag.closing.tag");
                        $output .= $node->get("tag.closing.postfix");
                    }
                }
            }
        }

        if (trim($output) == "") return false;

        return $output;
    }


    /**
     * execute the plugins before we do anything else
     *
     * @param Dom $dom
     * @return mixed|Error
     */
    private function preProcessPlugins($dom)
    {
        return $this->executePlugins($dom, "pre");
    }


    /**
     * execute the plugins on each individual node
     * children will parsed first
     *
     * @param Dom|array $dom
     * @param array $nodes
     * @return mixed|Error
     * @throws \Exception
     */
    private function processPlugins($dom, $nodes = NULL)
    {
        if (is_null($nodes)) {
            $nodes = $dom->get("nodes");
        }

        /** @var Node $node */
        foreach ($nodes as &$node) {
            $node = $this->executePlugins($node, "plugins");

            if ($node->has("children")) {
                $children = &$node->get("children");
                $this->processPlugins($dom, $children);
            }
        }

        return $dom;
    }


    /**
     * process the plugins after the main plugin process
     *
     * @param Dom $dom
     * @return mixed|Error
     * @throws \Exception
     */
    private function postProcessPlugins($dom)
    {
        return $this->executePlugins($dom, "post");
    }


    /**
     * process the plugins after rendering is complete
     *
     * @param string $output
     * @return mixed|Error
     * @throws \Exception
     */
    private function processOutputPlugins($output)
    {
        return $this->executePlugins($output, "output");
    }


    /**
     * processes all plugins depending on the passed type
     *
     * @param Dom|Node|string $element
     * @param string $type
     * @return mixed|Error
     */
    private function executePlugins($element, $type)
    {
        $plugins = $this->config->get("plugins.registered");
        foreach ($plugins as $key => $position) {
            /** @var Plugin $plugin */
            foreach ($position as $plugin) {
                try {
                    if ($type == "pre") {
                        $element = $plugin->preProcess($element);
                        $this->PluginError($element, $plugin, "preProcess", '$dom');
                    }
                    if ($type == "plugins") {
                        # only process if it's not disabled
                        if ($element->get("plugins")) {
                            $element = $plugin->realProcess($element);
                            $this->PluginError($element, $plugin, 'process', '$node');
                        }
                    }
                    if ($type == "post") {
                        $element = $plugin->postProcess($element);
                        $this->PluginError($element, $plugin, 'postProcess', '$dom');
                    }
                    if ($type == "output") {
                        $element = $plugin->processOutput($element);
                        $this->PluginError($element, $plugin, 'processOutput', '$output');
                    }
                } catch (\Exception $e) {
                    return new Error($e);
                }
            }
        }

        return $element;
    }


    /**
     * helper method for plugin return errors
     *
     * @param $element
     * @param $plugin
     * @param $method
     * @param $variable
     * @throws \Exception
     */
    private function PluginError($element, $plugin, $method, $variable)
    {
        $error = false;
        if ($variable == '$dom' && get_class($element) != "Caramel\\Models\\Dom") $error = true;
        if ($variable == '$node' && get_class($element) != "Caramel\\Models\\Node") $error = true;
        if ($variable == '$output' && gettype($element) != "string") $error = true;

        if ($error) {
            $pluginName = str_replace("Caramel\\Plugin", "", get_class($plugin));
            throw new \Exception("You need to return the variable: {$variable} </br></br>Plugin: {$pluginName} </br>Method: {$method} </br></br>");
        }
    }

}


