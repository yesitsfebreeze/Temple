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
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->config  = $caramel->config();
        $this->cache   = $caramel->cache;
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
            if ($node->get("display") && $node->get("tag/opening/display")) {
                $output .= $node->get("tag/opening/prefix");
                $output .= $node->get("tag/opening/tag");
                if ($node->get("tag/opening/tag") != "") {
                    $output .= " ";
                }
                $output .= $node->get("attributes");
                $output .= $node->get("tag/opening/postfix");
            }

            # recursively iterate over the children
            if ($node->has("children")) {
                if (!$node->get("selfclosing")) {
                    $children = $node->get("children");
                    $output .= $this->output($children);
                } else {
                    new Error("You can't have children in an " . $node->get("tag") . "!", $node->get("file"), $node->get("line"));
                }
            }

            # close the tag
            if ($node->get("display") && $node->get("tag/closing/display")) {
                if (!$node->get("selfclosing")) {
                    $output .= $node->get("tag/closing/prefix");
                    $output .= $node->get("tag/closing/tag");
                    $output .= $node->get("tag/closing/postfix");
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
        return $this->executePlugins($dom, "pre");
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
            $node = $this->executePlugins($node, "plugins");

            # check of node has children,
            # if so process each of them recursively
            if ($node->has("children")) {
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
        return $this->executePlugins($dom, "post");
    }

    /**
     * @param $output
     * @return Error
     * @throws Exception
     */
    private function processOutputPlugins($output)
    {
        return $this->executePlugins($output, "output");
    }

    /**
     * @param array|Storage $element
     * @param $type
     * @return mixed|Error
     */
    private function executePlugins($element, $type)
    {
        foreach ($this->plugins as $key => $position) {
            foreach ($position as $plugin) {
                try {
                    if ($type == "pre") {
                        $element = $plugin->preProcess($element);
                        if (is_null($element)) {
                            throw new \Exception("You need to return the dom in the preProcess method!");
                        }
                    }
                    if ($type == "plugins") {
                        # only process if it's not disabled
                        if ($element->get("plugins")) {
                            $element = $plugin->process($element);
                            if (is_null($element)) {
                                throw new \Exception("You need to return the node in the process method!");
                            }
                        }
                    }
                    if ($type == "post") {
                        $element = $plugin->postProcess($element);
                        if (is_null($element)) {
                            throw new \Exception("You need to return the dom in the postProcess method!");
                        }
                    }
                    if ($type == "output") {
                        $element = $plugin->processOutput($element);
                        if (is_null($element)) {
                            throw new \Exception("You need to return the output in the processOutput method!");
                        }
                    }
                } catch (Exception $e) {
                    return new Error($e);
                }
            }
        }

        return $element;
    }

}


