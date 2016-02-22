<?php

namespace Caramel;


/**
 *
 * Class PluginExtend
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
class PluginExtend extends Plugin
{

    /** @var int $position */
    protected $position = 0;

    /** @var array $blocks */
    private $blocks = array();

    /** @var string $file */
    private $rootFile = false;

    /**
     * hide the blocks from being displayed
     * except we set show_blocks in the config
     *
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        $node = $this->hideBlocks($node);

        return $node;
    }

    /**
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    public function preProcess($dom)
    {
        $node = reset($dom);
        if ($this->isExtending($node)) {
            $this->addExtendToSelfClosing();
            # add the file dependency to the parser
            $this->addDependency($node);
            $this->extend($dom, $node);
        }

        return $dom;
    }

    /**
     * @param array $dom
     * @param Node $node
     * @return array|Error
     */
    private function extend($dom, $node)
    {

        # get the root file
        $this->getFirstFile($node);

        # get all extending blocks from our current dom
        $this->getPreviousBlocks($dom);

        # get the dom which will be extended
        $dom = $this->getExtendDom($node);

        # override all blocks in the current dom
        $dom = $this->overrideBlocks($dom, $this->blocks);

        # if the current extend file is the same as our root file,
        # we would run into an recursion so we have to throw an error
        if ($node == $this->rootFile) {
            return new Error("Recursive extends are not allowed!", $this->rootFile, 1);
        }

        # we have to reinitialize the parsing process
        # with the new dom to check for other extends
        $this->caramel->parser->parse($this->rootFile, $dom);

        # return a empty array to stop the current parsing process
        return array();
    }

    /**
     * @param Node $node
     */
    protected function getFirstFile($node)
    {
        # just set the file if it's set to false
        # this way we can keep track of our root file
        if (!$this->rootFile) $this->rootFile = $node->get("file");
    }

    /**
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    private function getPreviousBlocks($dom)
    {
        /** @var Node $node */
        foreach ($dom as $node) {
            if ($node->get("tag/tag") == "block") {
                $name = trim($node->get("attributes"));
                # create array if it doesn't exist
                if (!isset($this->blocks[ $name ])) $this->blocks[ $name ] = array();
                $this->blocks[ $name ][] = $node;
            }
        }
    }

    /**
     * @param Node $node
     * @return Storage
     */
    private function getExtendDom($node)
    {
        $dom = false;
        /** @var Storage $node */
        $path = trim($node->get("attributes"));
        if ($path != "") {
            $path = str_replace("." . $this->config->get("extension"), "", $path);
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
            # get parent file with level and names space
            $dom = $this->caramel->lexer->lex($node->get("namespace"), $node->get("level") + 1)["dom"];
        }

        # in case we still fail somehow, at least give the user an error.
        if (!$dom) {
            return new Error("Seems like the parser crashed!", $node->get("file"), $node->get("line"));
        }

        return $dom;

    }

    /**
     * @param $dom
     * @param $blocks
     * @return mixed
     * @throws \Exception
     */
    private function overrideBlocks($dom, $blocks)
    {
        /** @var Storage $node */
        foreach ($dom as &$node) {

            # process children blocks first
            if ($node->has("children")) {
                if ($node->has("children")) {
                    $children = $node->get("children");
                    if (is_null($children)) $children = array();
                    $this->overrideBlocks($children, $blocks);
                }
            }

            if ($node->get("tag/tag") == "block") {

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
     * @param Node $node
     * @param $replace
     * @return Storage
     */
    private function replace($node, &$replace)
    {
        if (!is_null($replace)) {
            # update the node namespace
            $node->set("namespace", reset($replace)->get("namespace"));
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
     * @param Node $node
     * @param $wraps
     * @return mixed
     */
    private function wrap($node, &$wraps)
    {
        if (!is_null($wraps)) {
            # update the node namespace
            $node->set("namespace", reset($wraps)->get("namespace"));
            foreach ($wraps as &$wrap) {
                /*** @var Node $last */
                $last = $this->getLastWrapChild($wrap);
                $last->set("children", array($node));
                $node = $wrap;
            }
        }

        return $node;
    }

    /**
     * @param Node $node
     * @return bool
     */
    private function getLastWrapChild(&$node)
    {
        /** @var Storage $node */
        if ($node->has("children")) {
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
     * @param Node $node
     * @param $prepend
     * @return Storage
     */
    private function prepend($node, &$prepend)
    {
        /** @var Storage $node */
        if (!is_null($prepend)) {
            # update the node namespace
            $node->set("namespace", reset($prepend)->get("namespace"));
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
     * @param Node $node
     * @param $append
     * @return Storage
     */
    private function append($node, &$append)
    {
        /** @var Storage $node */
        if (!is_null($append)) {
            # update the node namespace
            $node->set("namespace", reset($append)->get("namespace"));
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
     * @param Node $node
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

    /**
     * @param Node $node
     * @return bool|mixed
     */
    private function isExtending($node)
    {

        # check if node hast extend tag
        if ($node->get("tag/tag") == "extend") {

            $node->set("display", false);

            # extend has to be first statement
            if ($node->get("line") != 1) {
                return new Error("'extend' hast to be the first statement!", $node->get("file"), $node->get("line"));
            }

            $isRootLevel   = $node->get("level") >= sizeof($this->config->getDirectoryHandler()->getTemplateDir()) - 1;
            $hasAttributes = $node->get("attributes") != "";

            # level must be smaller than our amount of template directories
            # if we want to extend the parent directory
            if (!$hasAttributes && $isRootLevel) {
                return new Error("You'r trying to extend a file without a parent template!", $node->get("file"), $node->get("line"));
            }

            # passed all testing
            return $node;

        }

        return false;
    }

    /**
     * @param Node $node
     * @return Node
     */
    private function hideBlocks($node)
    {
        if ($node->get("tag/tag") == "block") {

            if ($this->config->get("show_blocks")) {
                # shows name and namespace attributes as a comment for the block
                $node->set("attributes", $node->get("attributes") . " -> " . $node->get("namespace"));
                $node->set("tag/opening/prefix", "<!-- ");
                $node->set("tag/opening/postfix", " -->");
                $node->set("tag/closing/display", false);
            } else {
                # just hide the blocks
                $node->set("display", false);
            }
        }

        return $node;
    }

    /**
     * adds the extend tag to the self closing elements
     */
    private function addExtendToSelfClosing()
    {
        # add the extend tag to our self closing config array
        $selfClosing = $this->config->get("self_closing");
        if (!in_array("extend", $selfClosing)) {
            $selfClosing[] = "extend";
            $this->config->set("self_closing", $selfClosing);
        }
    }

    /**
     * @param Node $node
     */
    private function addDependency($node)
    {
        $this->caramel->cache->addDependency($node->get("file"));
    }

}