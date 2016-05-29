<?php

namespace Temple\Services;


use Temple\BaseClasses\DependencyBaseClass;
use Temple\BaseClasses\DomBaseClass;
use Temple\Exceptions\TempleException;
use Temple\Nodes\BaseNode;


/**
 * Class Parser
 *
 * @package Temple
 */
class ParserService extends DependencyBaseClass
{

    /**
     * @param DomBaseClass $dom
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
     * @param DomBaseClass $dom
     * @return DomBaseClass $dom
     */
    private function processPlugins($dom) {
        if ($dom->has("nodes")) {
            $nodes = $dom->get("nodes");
            foreach ($nodes as $node) {
                $this->pluginFactory->getPluginsForNode($node);
//                 todoo: plugin processor must still be written
            }
        }
        return $dom;
    }

    /**
     * checks if we have a valid dom object
     *
     * @param DomBaseClass $dom
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


    /**
     * merges the nodes into the final forntend string
     *
     * @param DomBaseClass|BaseNode $dom
     * @return mixed
     * @throws TempleException
     */
    public function createOutput($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $dom->get("nodes");
        foreach ($nodes as $node) {
            /** @var BaseNode $node */
            # open the tag
            if ($node->get("tag.display")) {
                if ($node->get("info.display") && $node->get("tag.opening.display")) {
                    $output .= $node->get("tag.opening.before");
                    $output .= $node->get("tag.opening.tag");
                    if ($node->get("tag.opening.tag") != "") {
                        $output .= " ";
                    }
                    foreach ($node->get("attributes") as $attribute) {
                        if (isset($attribute["name"])) {
                            $output .= $attribute["name"];
                            if (isset($attribute["value"])) {
                                $output .= "=";
                                $output .= $attribute["value"];
                            }
                            $output .= " ";
                        }
                    }
                    $output .= $node->get("tag.opening.after");
                }
            }

            if ($node->has("content")) {
                $content = $node->get("content");
                if (is_string($content)) {
                    $output .= $content;
                }
            }

            # recursively iterate over the children
            if ($node->has("children")) {
                if (!$node->get("info.selfclosing")) {
                    $children = new DomBaseClass();
                    $children->set("nodes", $node->get("children"));
                    $output .= $this->createOutput($children);
                } else {
                    throw new TempleException("You can't have children in an " . $node->get("tag.tag") . "!", $node->get("file"), $node->get("line"));
                }
            }

            # close the tag
            if ($node->get("tag.display")) {
                if ($node->get("info.display") && $node->get("tag.closing.display") && $node->get("tag.display")) {
                    if (!$node->get("info.selfclosing")) {
                        $output .= $node->get("tag.closing.before");
                        $output .= $node->get("tag.closing.tag");
                        $output .= $node->get("tag.closing.after");
                    }
                }
            }
        }

        if (trim($output) == "") return false;

        return $output;
    }






//    }
//
//
//    /**
//     * execute the plugins before we do anything else
//     *
//     * @param DomBaseClass $dom
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
//     * @param DomBaseClass|array $dom
//     * @param array          $nodes
//     * @return mixed
//     */
//    private function processPlugins($dom, $nodes = NULL)
//    {
//        if (is_null($nodes)) {
//            $nodes = $dom->get("nodes");
//        }
//
//        /** @var BaseNode $node */
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
//     * @param DomBaseClass $dom
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
//     * @param DomBaseClass|BaseNode|string $element
//     * @param string                        $type
//     * @return mixed
//     * @throws TempleException
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
//     * @throws TempleException
//     */
//    private function PluginError($element, $plugin, $method, $variable)
//    {
//        $error = false;
//        if ($variable == '$dom' && get_class($element) != "Temple\\Models\\DomBaseClass") $error = true;
//        if ($variable == '$node' && get_class($element) != "Temple\\Models\\BaseNode") $error = true;
//        if ($variable == '$output' && !is_string($element)) $error = true;
//
//        if ($error) {
//            $pluginName = str_replace("Temple\\Plugins", "", get_class($plugin));
//            throw new TempleException("You need to return the variable: {$variable} </br></br>Plugins: {$pluginName} </br>Method: {$method} </br></br>");
//        }
//    }

}


