<?php

namespace Pavel\Plugins;


use Pavel\Exception\Exception;
use Pavel\Models\Dom;
use Pavel\Models\HtmlNode;
use Pavel\Models\Plugin;


/**
 * Class PluginExtend
 *
 * @package     Pavel
 * @description handles the extending of files and bricks
 * @position    1
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Extend extends Plugin
{

    private $brickInsertMethods = array("before", "after", "wrap", "replace");


    /**
     * handles the extending of templates
     *
     * @param Dom $dom
     *
     * @return array
     * @throws \Exception
     */
    public function process($dom)
    {
        
        # add the extend tag to the selfClosing elements
        $this->Instance->Config()->extend("parser.selfClosing", "extend");

        # get the first node from the dom
        $nodes = $dom->get("nodes");
        $node  = reset($nodes);

        if ($node instanceof HtmlNode) {
            $fileToExtend = $this->getFileToExtend($node);
            if ($fileToExtend) {
                $dom = $this->extend($fileToExtend, $dom);
            }
        }

        return $dom;
    }


    /**
     * checks if the file has an valid extend tag
     *
     * @param HtmlNode $node
     *
     * @return bool|mixed
     * @throws Exception
     */
    private function getFileToExtend(HtmlNode $node)
    {

        # check if node has "extend" tag
        if ($node->get("tag.definition") == "extend" || $node->get("tag.definition") == "extends") {

            $node->set("tag.display", false);

            # if there is no file file passed
            if (sizeof($node->get("attributes")) == 0) {
                throw new Exception("Please pass a file to extend!", $node->get("info.file"), $node->get("info.line"));
            }

            $fileToExtend = array_keys($node->get("attributes"))[0];

            $exists = $this->Instance->Template()->templateExists($fileToExtend);

            if (!$exists) {
                throw new Exception("Can not extend from file '{$fileToExtend}', because it does not exist!", $node->get("info.file"), $node->get("info.line"));
            }

            return $fileToExtend;
        }

        return false;
    }


    /**
     * extends the current file and replaces all bricks
     * this will restart the parsing process
     *
     * @param String $fileToExtend
     * @param Dom    $dom
     *
     * @return array
     * @throws Exception
     */
    private function extend($fileToExtend, Dom $dom)
    {

        $root   = $dom->get("info.namespace");
        $bricks = $this->collectBricks($dom);

        # from now on we have the dom from the extended file
        $dom = $this->getNewDom($fileToExtend);
        $dom->set("info.parent.namespace", $root);
        $dom->set("info.bricks", $bricks);

        return $dom;
    }


    /**
     * @param $file
     *
     * @return Dom
     */
    private function getNewDom($file)
    {
        return $this->Instance->Template()->dom($file);
    }


    /**
     * returns all bricks from the template which extends
     *
     * @param Dom $dom
     *
     * @return array
     */
    private function collectBricks(Dom $dom)
    {
        $bricks = array();
        $nodes  = $dom->get("nodes");
        if (sizeof($nodes) > 0) {
            /** @var HtmlNode $node */
            foreach ($nodes as $node) {
                if ($node->get("tag.definition") == "brick") {

                    $attributes = $node->get("attributes");

                    if (isset($attributes["name"])) {
                        $name = $attributes["name"];
                    } else {
                        $name = implode(" ", array_keys($attributes));
                        foreach ($this->brickInsertMethods as $brickInsertMethod) {
                            $name = preg_replace("/" . $brickInsertMethod . "$/", "", $name);
                        }
                        $name = trim($name);
                    }

                    if (isset($attributes["insert"])) {
                        $insert = $attributes["insert"];
                    } else {
                        $insert = array_reverse(array_keys($attributes))[0];
                        if (!in_array($insert, $this->brickInsertMethods)) {
                            $insert = "replace";
                        }
                    }

                    if (!isset($bricks[ $name ])) {
                        $bricks[ $name ] = array();
                    }
                    if (!isset($bricks[ $name ][ $insert ])) {
                        $bricks[ $name ][ $insert ] = array();
                    }

                    $bricks[ $name ][ $insert ][] = $node;
                }
            }
        }

        return $bricks;
    }

}