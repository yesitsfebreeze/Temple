<?php

namespace Caramel\Services;


use Caramel\Exception\CaramelException;
use Caramel\Models\DomModel;
use Caramel\Nodes\NodeModel;
use Caramel\Models\Plugin;
use Caramel\Models\ServiceModel;


/**
 * Class Parser
 *
 * @package Caramel
 */
class ParserService extends ServiceModel
{

    /**
     * @param DomModel $dom
     * @return bool
     */
    public function parse($dom)
    {

        $this->cacheService->save($dom->get("info.file"), "");
        $output = $this->processPLugins($dom);
        $output = $this->createOutput($output);
        $this->cacheService->save($dom->get("info.file"), $output);

        return $output;
    }


    /**
     * @param DomModel $dom
     * @return string
     */
    private function processPlugins($dom) {
        if ($dom->has("nodes")) {
            $nodes = $dom->get("nodes");
            foreach ($nodes as $node) {
                $this->pluginFactory->getForNode($node);
            }
        }
    }

    /**
     * checks if we have a valid dom object
     *
     * @param DomModel $dom
     * @return bool
     */
    private function validateDom($dom)
    {
        if ($dom->has("nodes")) {
            $return = $dom->get("nodes");

            return empty($return);
        } else {
            return true;
        }
    }
























//
//
//    /**
//     * merges the nodes to a string
//     *
//     * @param DomModel|mixed $dom
//     * @return string
//     * @throws CaramelException
//     */
//    private function output($dom)
//    {
//        # temp variable for the output
//        $output = '';
//        $nodes  = $dom->get("nodes");
//        foreach ($nodes as $node) {
//            /** @var NodeModel $node */
//            # open the tag
//            if ($node->get("tag.display")) {
//                if ($node->get("info.display") && $node->get("tag.opening.display")) {
//                    $output .= $node->get("tag.opening.before");
//                    $output .= $node->get("tag.opening.tag");
//                    if ($node->get("tag.opening.tag") != "") {
//                        $output .= " ";
//                    }
//                    foreach ($node->get("attributes") as $attribute) {
//                        if (isset($attribute["name"])) {
//                            $output .= $attribute["name"];
//                            if (isset($attribute["value"])) {
//                                $output .= "=";
//                                $output .= $attribute["value"];
//                            }
//                            $output .= " ";
//                        }
//                    }
//                    $output .= $node->get("tag.opening.after");
//                }
//            }
//
//            if ($node->has("content")) {
//                $content = $node->get("content");
//                if (is_string($content)) {
//                    $output .= $content;
//                }
//            }
//
//            # recursively iterate over the children
//            if ($node->has("children")) {
//                if (!$node->get("info.selfclosing")) {
//                    $children = new DomModel();
//                    $children->set("nodes", $node->get("children"));
//                    $output .= $this->output($children);
//                } else {
//                    throw new CaramelException("You can't have children in an " . $node->get("tag.tag") . "!", $node->get("file"), $node->get("line"));
//                }
//            }
//
//            # close the tag
//            if ($node->get("tag.display")) {
//                if ($node->get("info.display") && $node->get("tag.closing.display") && $node->get("tag.display")) {
//                    if (!$node->get("info.selfclosing")) {
//                        $output .= $node->get("tag.closing.before");
//                        $output .= $node->get("tag.closing.tag");
//                        $output .= $node->get("tag.closing.after");
//                    }
//                }
//            }
//        }
//
//        if (trim($output) == "") return false;
//
//        return $output;
//    }
//
//
//    /**
//     * execute the plugins before we do anything else
//     *
//     * @param DomModel $dom
//     * @return mixed
//     */
//    private function preProcessPlugins($dom)
//    {
//        return $this->executePlugins($dom, "pre");
//    }
//
//
//    /**
//     * execute the plugins on each individual node
//     * children will parsed first
//     *
//     * @param DomModel|array $dom
//     * @param array          $nodes
//     * @return mixed
//     */
//    private function processPlugins($dom, $nodes = NULL)
//    {
//        if (is_null($nodes)) {
//            $nodes = $dom->get("nodes");
//        }
//
//        /** @var NodeModel $node */
//        foreach ($nodes as &$node) {
//            $node = $this->executePlugins($node, "plugins");
//
//            if ($node->has("children")) {
//                $children = &$node->get("children");
//                $this->processPlugins($dom, $children);
//            }
//        }
//
//        return $dom;
//    }
//
//
//    /**
//     * process the plugins after the main plugin process
//     *
//     * @param DomModel $dom
//     * @return mixed
//     */
//    private function postProcessPlugins($dom)
//    {
//        return $this->executePlugins($dom, "post");
//    }
//
//
//    /**
//     * process the plugins after rendering is complete
//     *
//     * @param string $output
//     * @return mixed
//     */
//    private function processOutputPlugins($output)
//    {
//        return $this->executePlugins($output, "output");
//    }
//
//
//    /**
//     * processes all plugins depending on the passed type
//     *
//     * @param DomModel|NodeModel|string $element
//     * @param string                        $type
//     * @return mixed
//     * @throws CaramelException
//     */
//    private function executePlugins($element, $type)
//    {
//
////        $plugins = $this->plugins->getPlugins();
////        foreach ($plugins as $key => $position) {
////            /** @var PluginModel $plugin */
////            foreach ($position as $plugin) {
////                if ($type == "pre") {
////                    $element = $plugin->preProcess($element);
////                    $this->PluginError($element, $plugin, "preProcess", '$dom');
////                }
////                if ($type == "plugins") {
////                    # only process if it's not disabled
////                    if ($element->get("plugins")) {
////                        $element = $plugin->realProcess($element);
////                        $this->PluginError($element, $plugin, 'process', '$node');
////                    }
////                }
////                if ($type == "post") {
////                    $element = $plugin->postProcess($element);
////                    $this->PluginError($element, $plugin, 'postProcess', '$dom');
////                }
////                if ($type == "output") {
////                    $element = $plugin->processOutput($element);
////                    $this->PluginError($element, $plugin, 'processOutput', '$output');
////                }
////            }
////        }
//
//        return $element;
//    }
//
//
//    /**
//     * helper method for plugin return errors
//     *
//     * @param $element
//     * @param $plugin
//     * @param $method
//     * @param $variable
//     * @throws CaramelException
//     */
//    private function PluginError($element, $plugin, $method, $variable)
//    {
//        $error = false;
//        if ($variable == '$dom' && get_class($element) != "Caramel\\Models\\DomModel") $error = true;
//        if ($variable == '$node' && get_class($element) != "Caramel\\Models\\NodeModel") $error = true;
//        if ($variable == '$output' && !is_string($element)) $error = true;
//
//        if ($error) {
//            $pluginName = str_replace("Caramel\\Plugins", "", get_class($plugin));
//            throw new CaramelException("You need to return the variable: {$variable} </br></br>Plugins: {$pluginName} </br>Method: {$method} </br></br>");
//        }
//    }

}


