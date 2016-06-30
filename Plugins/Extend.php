<?php

namespace Shift\Plugin;


use Shift\Exception\ShiftException;
use Shift\Models\Dom;
use Shift\Models\HtmlNode;
use Shift\Models\Plugin;


/**
 * Class PluginExtend
 *
 * @package     Shift
 * @description handles the extending of files and blocks
 * @position    1
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Extend extends Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 1;
    }


    public function isDomProcessor()
    {
        return true;
    }


    private $blockInsertMethods = array("before", "after", "wrap", "replace");


    /**
     * handles the extending of templates
     *
     * @param Dom $dom
     *
     * @return array
     * @throws \Exception
     */
    public function process(Dom $dom)
    {

        # add the extend tag to the selfclosing elements
        $this->Shift->Config()->extend("parser.selfClosing", "extend");

        # get the first node from the dom
        $nodes = $dom->get("nodes");
        $node  = reset($nodes);

        $fileToExtend = $this->getFileToExtend($node);

        if ($fileToExtend) {
            $dom = $this->extend($fileToExtend, $dom);
        }

        return $dom;
    }


    /**
     * checks if the file has an valid extend tag
     *
     * @param HtmlNode $node
     *
     * @return bool|mixed
     * @throws ShiftException
     */
    private function getFileToExtend(HtmlNode $node)
    {

        # check if node has "extend" tag
        if ($node->get("tag.definition") == "extend" || $node->get("tag.definition") == "extends") {

            $node->set("tag.display", false);

            # if there is no file file passed
            if (sizeof($node->get("attributes")) == 0) {
                throw new ShiftException("Please pass a file to extend!", $node->get("info.file"), $node->get("info.line"));
            }

            $fileToExtend = array_keys($node->get("attributes"))[0];

            $exists = $this->Shift->Template()->templateExists($fileToExtend);

            if (!$exists) {
                throw new ShiftException("Can not extend from file '{$fileToExtend}', because it does not exist!", $node->get("info.file"), $node->get("info.line"));
            }

            return $fileToExtend;
        }

        return false;
    }


    /**
     * extends the current file and replaces all blocks
     * this will restart the parsing process
     *
     * @param String $fileToExtend
     * @param Dom    $dom
     *
     * @return array
     * @throws ShiftException
     */
    private function extend($fileToExtend, Dom $dom)
    {

        $root   = $dom->get("info.namespace");
        $blocks = $this->gatherBlocks($dom);

        # from now on we have the dom from the extended file
        $dom = $this->getNewDom($fileToExtend);
        $dom->set("info.parent.namespace", $root);
        $dom->set("info.blocks", $blocks);

        return $dom;
    }


    /**
     * @param $file
     *
     * @return Dom
     */
    private function getNewDom($file)
    {
        return $this->Shift->template()->dom($file);
    }


    /**
     * returns all blocks from the template which extends
     *
     * @param Dom $dom
     *
     * @return array
     */
    private function gatherBlocks(Dom $dom)
    {
        $blocks = array();
        $nodes  = $dom->get("nodes");
        if (sizeof($nodes) > 0) {
            /** @var HtmlNode $node */
            foreach ($nodes as $node) {
                if ($node->get("tag.definition") == "block") {

                    $attributes = $node->get("attributes");

                    if (isset($attributes["name"])) {
                        $name = $attributes["name"];
                    } else {
                        $name = implode(" ", array_keys($attributes));
                        foreach ($this->blockInsertMethods as $blockInsertMethod) {
                            $name = preg_replace("/" . $blockInsertMethod . "$/", "", $name);
                        }
                        $name = trim($name);
                    }

                    if (isset($attributes["insert"])) {
                        $insert = $attributes["insert"];
                    } else {
                        $insert = array_reverse(array_keys($attributes))[0];
                        if (!in_array($insert,$this->blockInsertMethods)) {
                            $insert = "replace";
                        }
                    }

                    if (!isset($blocks[ $name ])) {
                        $blocks[ $name ] = array();
                    }
                    if (!isset($blocks[ $name ][ $insert ])) {
                        $blocks[ $name ][ $insert ] = array();
                    }
                    $blocks[ $name ][ $insert ][] = $node;
                }
            }
        }

        return $blocks;
    }

}