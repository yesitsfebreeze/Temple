<?php

namespace Caramel;


/**
 *
 * Class Caramel_Plugin_Extend
 *
 * @purpose: handles template extending and block overriding
 * @usage:
 *
 *      extends:
 *          extend: search for same file in parent tempalte if available
 *          extend file/relative: searches for file regarding to current file path
 *          extend /file/absolute: searches for file regarding to root path
 *
 *      blocks:
 *          block blockname: defines a block wich can be modified afterwards
 *          block prepend blockname: inserts content of this block before the "block blockname" block
 *          block wrap blockname: wraps content of the "block blockname" block with the content of our current block
 *          block append blockname: inserts content of this block after the "block blockname" block
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Extend extends PluginBase
{

    /** @var int $position */
    protected $position = 0;

    /** @var array $blocks */
    private $blocks = array();

    /** @var string $file */
    private $file = false;

    /**
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    public function preProcess($dom)
    {

        # add the extend tag to our self closing config array
        $selfClosing   = $this->config->get("self_closing");
        $selfClosing[] = "extend";
        $this->config->set("self_closing", $selfClosing);

        # add the file dependency to the parser
        $this->caramel->cache->addDependency(reset($dom)->get("file"));

        # check if the dom as an extend
        $node = $this->isExtending($dom);
        if ($node) {

            # get the file of our first dom element
            # to prevent the parser to save the extended dom
            # to a different file
            # fills $this->file
            $this->getFristFile($dom);

            # get all extending blocks from our current dom
            # fills $this->blocks
            $this->getTopLevelBlocks($dom);

            # get the dom which will be extended
            $dom = $this->getNextDom($node);

            # extending all blocks in the current dom
            $dom = $this->extend($dom, $this->blocks);

            # get the file of our extended dom
            # and check if it's the same as our
            # extending file
            $extendFile = reset($dom)->get("file");
            if ($extendFile == $this->file) {
                return new Error("Recursive extends are not allowed!", $this->file, 1);
            }

            # we have to reinitialize the parsing process for
            # our dom to check for other extends
            $this->caramel->parser->parse($this->file, $dom);

            # to stop the current parsing process,
            # we just return a empty array
            return array();

        } else {
            $this->blocks = array();

            return $dom;
        }


    }

    /**
     * @param $dom
     * @return bool|mixed
     */
    private function isExtending($dom)
    {
        if (sizeof($dom) > 0) {
            # get the first node
            $node = reset($dom);

            # check if node hast extend tag
            if ($node->get("tag") == "extend") {

                $isFirstLine = $node->get("line") != 1;

                # extend has to be first statement
                if ($isFirstLine) {
                    return new Error("'extend' hast to be the first statement!", $node->get("file"), $node->get("line"));
                }

                $isRootLevel   = $node->get("level") >= sizeof($this->config->getTemplateDir()) - 1;
                $hasAttributes = $node->get("attributes") != "";

                # level must be smaller than our amount of template directories
                # if we want to extend the parent directory
                if (!$hasAttributes && $isRootLevel) {
                    return new Error("You'r trying to extend a file without a parent template!", $node->get("file"), $node->get("line"));
                }

                # passed all testing
                return $node;

            }
        }

        return false;
    }

    /**
     * @param $dom
     */
    protected function getFristFile($dom)
    {
        # just set the file if it's set to false
        # this way we can keep track of our root file
        if (!$this->file) $this->file = reset($dom)->get("file");
    }

    /**
     * @param $node
     * @return Storage
     */
    private function getNextDom($node)
    {
        $dom = false;
        /** @var Storage $node */
        $path = trim($node->get("attributes"));
        if ($path != "") {
            # absolute extend
            if ($path[0] == "/") {
                $dom = $this->caramel->lexer->lex($path)["dom"];
            }
            # relative extend
            if ($path[0] != "/") {
                # remove last namespace from our node namespace,
                # so we are left with the folder
                $folder = explode("/", strrev($node->get("namespace")));
                array_shift($folder);
                $folder = strrev(implode("/", $folder));
                # concat folder and path to get full file path
                $path = $folder . "/" . $path;
                $dom  = $this->caramel->lexer->lex($path)["dom"];
            }
        } else {
            # get parent file with level and namesspace
            $dom = $this->caramel->lexer->lex($node->get("namespace"), $node->get("level") + 1)["dom"];
        }

        # in case we still fail somehow, at least give the user an error.
        if (!$dom) {
            return new Error("Seems like the parser crashed!", $node->get("file"), $node->get("line"));
        }

        return $dom;

    }

    /**
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    private function getTopLevelBlocks($dom)
    {
        /** @var Storage $block */
        foreach ($dom as $block) {
            if ($block->get("tag") == "block") {
                $name = trim($block->get("attributes"));
                # create array if it doesn't exist
                if (!isset($this->blocks[ $name ])) $this->blocks[ $name ] = array();
                $this->blocks[ $name ][] = $block;
            }
        }
    }

    /**
     * @param $dom
     * @param $blocks
     * @return mixed
     * @throws \Exception
     */
    private function extend($dom, $blocks)
    {
        /** @var Storage $node */
        foreach ($dom as &$node) {

            # process children blocks first
            if ($node->has("children")) {
                if ($node->has("children")) {
                    $children = $node->get("children");
                    if (is_null($children)) $children = array();
                    $this->extend($children, $blocks);
                }
            }

            if ($node->get("tag") == "block") {

                $block = trim($node->get("attributes"));

                # first add the last complete block override if exists
                $replace = &$blocks[ $block ];
                $node    = $this->replace($node, $replace);

                # then wrap the node afterwards
                $wrap = &$blocks[ 'wrap ' . $block ];
                $node = $this->wrap($node, $wrap);

                # add all prepends next
                $prepend = &$blocks[ 'prepend ' . $block ];
                $node    = $this->prepend($node, $prepend);

                # then add all appends
                $append = &$blocks[ 'append ' . $block ];
                $node   = $this->append($node, $append);

            }
        }

        return $dom;
    }

    /**
     * @param $node
     * @param $replace
     * @return Storage
     */
    private function replace($node, &$replace)
    {
        if (!is_null($replace)) {
            # update the node namespace
            $node->set("namespace",reset($replace)->get("namespace"));
            $replace = $replace[ sizeof($replace) - 1 ];
            # reset the children to a clean Storage
            # since we completely replace them
            $node->set("children", new Storage());
            # get children from the replace array
            $replaces = $replace->get("children");
            $node     = $this->merge($node, $replaces);
            unset($replace);
        }

        return $node;
    }

    /**
     * @param $node
     * @param $wraps
     * @return mixed
     */
    private function wrap($node, &$wraps)
    {
        if (!is_null($wraps)) {
            # update the node namespace
            $node->set("namespace",reset($wraps)->get("namespace"));
            foreach ($wraps as &$wrap) {
                $last = $this->getLastWrapChild($wrap);
                $last->set("children", array($node));
                $node = $wrap;
            }
        }

        return $node;
    }

    /**
     * @param $node
     * @return bool
     */
    private function getLastWrapChild(&$node)
    {
        /** @var Storage $node */
        if ($node->get("has_children")) {
            $children = $node->get("children");
            # since we wrap the children with stuff
            # it absolutely makes no sense to have more than one children
            if (sizeof($children) > 1) {
                return new Error("The 'block wrap' function can't have more than one children", $node->get("file"), $node->get("line"));
            } else {
                # get first entry and recurse
                $child = reset($children);

                return $this->getLastWrapChild($child);
            }
        } else {
            return $node;
        }

        return false;
    }

    /**
     * @param $node
     * @param $prepend
     * @return Storage
     */
    private function prepend($node, &$prepend)
    {
        /** @var Storage $node */
        if (!is_null($prepend)) {
            # update the node namespace
            $node->set("namespace",reset($prepend)->get("namespace"));
            # reverse the nodes to obtain right order
            $prepend  = array_reverse($prepend);
            $replaces = $node->get("children");
            $replaces = array_values($replaces);
            foreach ($prepend as $prepended) {
                $prepended = $prepended->get("children");
                # also reverse the children to obtain right order
                $prepended = array_reverse($prepended);
                foreach ($prepended as $child) {
                    # add all prepended nodes before our block
                    array_unshift($replaces, $child);
                }
            }
            # put it together
            $node = $this->merge($node, $replaces);
            unset($prepend);
        }

        return $node;
    }


    /**
     * @param $node
     * @param $append
     * @return Storage
     */
    private function append($node, &$append)
    {
        /** @var Storage $node */
        if (!is_null($append)) {
            # update the node namespace
            $node->set("namespace",reset($append)->get("namespace"));
            # reverse the nodes to obtain right order
            $append   = array_reverse($append);
            $replaces = $node->get("children");
            foreach ($append as $appended) {
                $appended = $appended->get("children");
                foreach ($appended as $child) {
                    # add all prepended nodes after our block
                    array_push($replaces, $child);
                }
            }
            # put it together

            $node = $this->merge($node, $replaces);
            unset($append);
        }

        return $node;
    }


    /**
     * @param $node
     * @param $replaces
     * @return Storage
     */
    private function merge($node, $replaces)
    {
        # always order the replacing nodes
        /** @var Storage $node */
        /** @var Storage $children */
        $node->set("children", array_values($replaces));

        return $node;
    }


}