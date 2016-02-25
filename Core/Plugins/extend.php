<?php

namespace Caramel;


/**
 * Class PluginExtend
 *
 * @package     Caramel
 * @description handles the extending of files and blocks
 * @position    1
 * @author      Stefan Hövelmanns
 * @License     MIT
 */

class PluginExtend extends Plugin
{

    /** @var int $position */
    protected $position = 1;

    /** @var array $blocks */
    private $blocks = array();

    /** @var bool $topLevel */
    private $topLevel = false;

    /** @var array $rootFiles */
    private $rootFiles = array();

    /** @var string $rootFile */
    private $rootFile;


    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag.tag") == "block");
    }


    /**
     * converts the blocks to comments or completely removes them
     * depending on configuration
     *
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        if ($this->crml->config()->get("show_block_as_comments")) {
            # hide parent blocks
            if ($node->get("attributes") == "parent") {
                $node->set("tag.display", false);
                return $node;
            }
            # shows name and namespace attributes as a comment for the block
            $node->set("attributes", $node->get("attributes") . " -> " . $node->get("namespace"));
            $node->set("tag.opening.prefix", "<!-- ");
            $node->set("tag.opening.postfix", " -->");
            $node->set("tag.closing.display", false);
        } else {
            # just hide the blocks
            $node->set("tag.display", false);
        }

        return $node;
    }


    /**
     * handles the extending of templates
     *
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    public function preProcess($dom)
    {
        # get the first node from the dom
        $node = reset($dom);

        if ($this->extending($node)) {

            $this->extend($dom, $node);

            # return a empty array to stop the current parsing process
            return array();
        }
        $this->rootFile = false;

        return $dom;
    }


    /**
     * checks if the file has an valid extend tag
     *
     * @param Node $node
     * @return bool|mixed
     */
    private function extending($node)
    {

        # check if node hast extend tag
        if ($node->get("tag.tag") == "extend") {

            $node->set("display", false);

            # extend has to be first statement
            if ($node->get("line") != 1) {
                return new Error("'extend' hast to be the first statement!", $node->get("file"), $node->get("line"));
            }

            $isRootLevel   = $node->get("level") >= sizeof($this->crml->template()->dirs()) - 1;
            $hasAttributes = $node->get("attributes") != "";

            # level must be smaller than our amount of template directories
            # if we want to extend the parent directory
            if (!$hasAttributes && $isRootLevel) {
                return new Error("You'r trying to extend a file without a parent template!", $node->get("file"), $node->get("line"));
            }

            # passed all testing
            return true;

        }

        return false;
    }


    /**
     * extends the current file and replaces all blocks
     * this will restart the parsing process
     *
     * @param array $dom
     * @param Node  $node
     * @return array|Error
     */
    private function extend($dom, $node)
    {

        $this->getRootFile($node);

        $this->getBlocks($dom);

        $dom = $this->getDom($node);

        # if the current extend file is the same as our root file,
        # we would run into an recursion so we have to throw an error
        if (!$this->topLevel && $node->get("file") == $this->rootFile) {
            return new Error("Recursive extends are not allowed!", $this->rootFile, 1);
        }

        $dom = $this->blocks($dom, $this->blocks);

        $this->crml->cache()->dependency($this->rootFile, reset($dom)->get("file"));

        # reset the variables
        $this->topLevel = false;

        # we have to reinitialize the parsing process
        # with the new dom to check for other extends
        $dom->set("template.file",$this->rootFile);
        return $this->crml->parser()->parse($dom);
    }


    /**
     * @param Node $node
     */
    protected function getRootFile($node)
    {
        # just set the file if it's set to false
        # this way we can keep track of our root file
        if (!isset($this->rootFiles[ $node->get("namespace") ])) {
            $this->rootFiles[ $node->get("namespace") ] = $node->get("file");
            $this->topLevel                             = true;
        }
        $this->rootFile = $this->rootFiles[ $node->get("namespace") ];
    }


    /**
     * get all extending blocks from our current dom
     *
     * @param array $dom
     * @return array
     * @throws \Exception
     */
    private function getBlocks($dom)
    {
        /** @var Node $node */
        foreach ($dom as $node) {
            if ($node->get("tag.tag") == "block") {
                $name = trim($node->get("attributes"));
                # create array if it doesn't exist
                if (!isset($this->blocks[ $name ])) $this->blocks[ $name ] = array();
                $this->blocks[ $name ][] = $node;
            }
        }
    }


    /**
     * returns the dom of the file which got extended
     *
     * @param Node $node
     * @return array
     */
    private function getDom($node)
    {
        $dom = false;
        /** @var Storage $node */
        $path = trim($node->get("attributes"));
        if ($path != "") {
            $path = str_replace("." . $this->config->get("extension"), "", $path);
            # absolute extend
            if ($path[0] == "/") {
                $dom = $this->crml->lexer()->lex($path)["dom"];
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
                $dom  = $this->crml->lexer()->lex($path)["dom"];
            }
        } else {
            # get parent file with level and names space
            $dom = $this->crml->lexer()->lex($node->get("namespace"), $node->get("level") + 1)["dom"];
        }

        # in case we still fail somehow, at least give the user an error.
        if (!$dom) {
            return new Error("Seems like the parser crashed!", $node->get("file"), $node->get("line"));
        }

        return $dom;

    }


    /**
     * extend all blocks in the current dom
     *
     * @param array $dom
     * @param array $blocks
     * @return mixed
     * @throws \Exception
     */
    private function blocks($dom, $blocks)
    {

        /** @var Node $node */
        foreach ($dom as &$node) {

            # process children blocks first
            if ($node->has("children")) {
                $children = $node->get("children");
                $this->blocks($children, $blocks);
            }

            if ($node->get("tag.tag") == "block") {
                $block = trim($node->get("attributes"));
                # first add the last complete block override if exists
                $replace = &$blocks[ 'replace ' . $block ];
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
                $node->set("extended", true);
            }
        }

        return $dom;
    }


    /**
     * replaces a block with the extending block
     *
     * @param Node  $node
     * @param array $replaces
     * @return Storage
     */
    private function replace($node, &$replaces)
    {

        if (!is_null($replaces)) {
            /** @var Node $replace */
            $replace = reset(array_reverse($replaces));
            # update the node namespace
            $node->set("namespace", $replace->get("namespace"));
            $node->set("children", array($replace));
            $replaces = NULL;
        }

        return $node;
    }


    /**
     * wraps a block with the extending block
     *
     * @param Node $node
     * @param      $wraps
     * @return mixed
     */
    private function wrap($node, &$wraps)
    {
        if (!is_null($wraps)) {

            # update the node namespace
            $node->set("namespace", reset($wraps)->get("namespace"));

            $children = $node->get("children");
            foreach ($wraps as $wrap) {
                /** @var Node $inner */
                $inner = $this->inner($wrap);
                $inner->set("children", $children);
                $node->set("children", array($wrap));
            }
        }

        return $node;
    }


    /**
     * get the most inner node
     *
     * @param Node $node
     * @return bool
     */
    private function inner(&$node)
    {
        /** @var Storage $node */
        if ($node->has("children")) {
            # get the last node and try to get its inner
            $child = reset(array_reverse($node->get("children")));

            # it absolutely makes no sense to have more than one children
            return $this->inner($child);

        } else {
            return $node;
        }
    }


    /**
     * adds the extending block content before the block
     *
     * @param Node       $node
     * @param array|Node $prepend
     * @return Node
     */
    private function prepend($node, &$prepend)
    {
        if (!is_null($prepend)) {
            # update the node namespace
            $node->set("namespace", reset($prepend)->get("namespace"));
            # reverse the nodes to obtain right order
            $prepend = array_reverse($prepend);
            # put it together
            foreach ($prepend as &$item) {
                $children = $node->get("children");
                array_unshift($children, $item);
                $node->set("children", $children);
                unset($append);
            }
        }

        return $node;
    }


    /**
     * adds the extending block content after the block
     *
     * @param Node       $node
     * @param array|Node $append
     * @return Node
     */
    private function append($node, &$append)
    {
        if (!is_null($append)) {
            # update the node namespace
            $node->set("namespace", reset($append)->get("namespace"));
            # reverse the nodes to obtain right order
            $append = array_reverse($append);
            # put it together
            foreach ($append as &$item) {
                $children = $node->get("children");
                array_push($children, $item);
                $node->set("children", $children);
                unset($append);
            }
        }

        return $node;
    }
}