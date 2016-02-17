<?php

namespace Caramel;

use Exception as Exception;

/**
 * Class Parser
 * @package Caramel
 */
class Parser
{

    /** @var  Config $config */
    private $config;

    /** @var  Cache $cache */
    private $cache;

    /** @var  Storage $dom */
    private $dom;

    /** @var  string $output */
    private $output;


    /**
     * Parser constructor.
     * @param Caramel $milk
     */
    public function __construct(Caramel $milk)
    {
        $this->config  = $milk->config();
        $this->cache   = $milk->cache;
        $this->plugins = $this->config->get("plugins/registered");
    }

    /**
     * @param array $dom
     * @param string $file
     * @return bool
     */
    public function parse($file, $dom)
    {

        $this->dom = $dom;
        if (empty($this->dom)) return $this->cache->save($file, "");

        # the returns make sure that the parse process
        # stops if we have an empty dom
        # this enables you to parse a modified dom in a plugin or function

        $this->dom = $this->preProcessPlugins($this->dom);
        if (empty($this->dom)) return $this->cache->save($file, "");

        $this->dom = $this->processPlugins($this->dom);
        if (empty($this->dom)) return $this->cache->save($file, "");

        $this->dom = $this->postProcessPlugins($this->dom);
        if (empty($this->dom)) return $this->cache->save($file, "");

        # parse and save the output
        $this->output = $this->output($this->dom);

        # process the output plugins
        if (trim($this->output) == "") return $this->cache->save($file, "");
        $this->output = $this->processOutputPlugins($this->output);

        $this->cache->save($file, $this->output);

        return $this->output;
    }

    /**
     * @param $nodes
     * @return string
     */
    private function output($nodes)
    {
        # temp variable for the output
        $output = '';

        foreach ($nodes as $node) {

            /** @var storage $node */

            # open the tag
            if ($node->get("display") && $node->get("start/display")) {
                $output .= $node->get("start/prefix");
                $output .= $node->get("start/tag");
                $output .= $node->get("attributes");
                $output .= $node->get("start/postfix");
            }

            # recursively iterate over the children

            if ($node->get("has_children")) {
                if (!$node->get("self_closing")) {
                    $children = $node->get("children");
                    $output .= $this->output($children);
                } else {
                    new Error("You can't have children in an " . $node->get("tag") . "!", $node->get("file"), $node->get("line"));
                }
            }

            # close the tag
            if ($node->get("display") && $node->get("end/display")) {
                if (!$node->get("self_closing")) {
                    $output .= $node->get("end/prefix");
                    $output .= $node->get("end/tag");
                    $output .= $node->get("end/postfix");
                }
            }
        }

        return $output;
    }

    /**
     * @param $dom
     * @return Error
     * @throws Exception
     */
    private function preProcessPlugins($dom)
    {
        return $this->iteratePlugins($dom, "pre");
    }

    /**
     * @param $nodes
     * @return mixed
     * @throws Exception
     */
    private function processPlugins($nodes)
    {
        /** @var Storage $node */
        foreach ($nodes as &$node) {
            # process current node
            $node = $this->iteratePlugins($node, "plugins");

            # check of node has children,
            # if so process each of them recursively
            if ($node->get("has_children")) {
                $children = &$node->get("children");
                $this->processPlugins($children);
            }

        }

        return $nodes;
    }

    /**
     * @param $dom
     * @return Error
     * @throws Exception
     */
    private function postProcessPlugins($dom)
    {
        return $this->iteratePlugins($dom, "post");
    }

    /**
     * @param $dom
     * @return Error
     * @throws Exception
     */
    private function processOutputPlugins($output)
    {
        return $this->iteratePlugins($output, "output");
    }

    /**
     * @param array|Storage $element
     * @param $type
     * @return mixed|Error
     */
    private function iteratePlugins($element, $type)
    {
        /** @var PluginBase $plugin */
        foreach ($this->plugins as $key => $position) {
            foreach ($position as $plugin) {
                try {
                    if ($type == "pre") {
                        $element = $plugin->preProcess($element);
                    }
                    if ($type == "plugins") {
                        # only process if it's not disabled
                        if ($element->get("process_plugins")) {
                            $element = $plugin->process($element);
                        }
                    }
                    if ($type == "post") {
                        $element = $plugin->postProcess($element);
                    }
                    if ($type == "output") {
                        $element = $plugin->processOutput($element);
                    }
                } catch (Exception $e) {
                    return new Error($e);
                }
            }
        }

        return $element;
    }

}


