<?php

namespace Caramel;


use Caramel\Exceptions\CaramelException;
use Caramel\Models\Dom;
use Caramel\Models\Node;


/**
 * Class PluginExtend
 *
 * @package     Caramel
 * @description handles the extending of files and blocks
 * @position    1
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class PluginExtend extends Models\Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 1;
    }

    /** @var array $blocks */
    private $blockStash = array();

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
        if ($this->caramel->config()->get("block_comments")) {
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
     * @param Dom $dom
     * @return array
     * @throws \Exception
     */
    public function preProcess($dom)
    {
        $this->caramel->config()->extend("self_closing","extend");
        # get the first node from the dom
        $nodes = $dom->get("nodes");
        $node  = reset($nodes);
        if ($this->extending($node)) {

            $this->extend($dom, $node);

            # return a empty array to stop the current parsing process
            return new Dom();
        }
        $this->rootFile = false;

        return $dom;
    }


    /**
     * checks if the file has an valid extend tag
     *
     * @param Node $node
     * @return bool|mixed
     * @throws CaramelException
     */
    private function extending($node)
    {

        # check if node hast extend tag
        if ($node->get("tag.tag") == "extend") {

            $node->set("display", false);

            # extend has to be first statement
            if ($node->get("line") != 1) {
                throw new CaramelException("'extend' hast to be the first statement!", $node->get("file"), $node->get("line"));
            }

            $isRootLevel   = $node->get("level") >= sizeof($this->caramel->template()->dirs()) - 1;
            $hasAttributes = $node->get("attributes") != "";

            # level must be smaller than our amount of template directories
            # if we want to extend the parent directory
            if (!$hasAttributes && $isRootLevel) {
                throw new CaramelException("You'r trying to extend a file without a parent template!", $node->get("file"), $node->get("line"));
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
     * @param Dom  $dom
     * @param Node $node
     * @return array
     * @throws CaramelException
     */
    private function extend($dom, $node)
    {
        $this->getRootFile($node);
        $this->getBlocks($dom);
        $dom = $this->getDom($node);

        # if the current extend file is the same as our root file,
        # we would run into an recursion so we have to throw an error
        if (!$this->topLevel && $node->get("file") == $this->rootFile) {
            throw new CaramelException("Recursive extends are not allowed!", $this->rootFile, 1);
        }

        $dom = $this->blocks($dom, $this->blockStash);

        $this->caramel->cache()->dependency($this->rootFile, reset($dom->get("nodes"))->get("file"));

        # reset the variables
        $this->topLevel = false;

        # we have to reinitialize the parsing process
        # with the new dom to check for other extends
        $dom->set("template.file", $this->rootFile);

        return $this->caramel->parser()->parse($dom);
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
     * @param Dom $dom
     * @return array
     * @throws \Exception
     */
    private function getBlocks($dom)
    {
        /** @var Node $node */
        $nodes = $dom->get("nodes");
        foreach ($nodes as $node) {
            if ($node->get("tag.tag") == "block") {
                $name = trim($node->get("attributes"));
                # create array if it doesn't exist
                if (!isset($this->blockStash[ $name ])) $this->blockStash[ $name ] = array();
                $this->blockStash[ $name ][] = $node;
            }
        }
    }



    /**
     * returns the dom of the file which got extended
     *
     * @param Node $node
     * @return Dom
     * @throws CaramelException
     */
    private function getDom($node)
    {
        $dom = false;
        /** @var Node $node */
        $path = trim($node->get("attributes"));
        if ($path != "") {
            $path = str_replace("." . $this->caramel->config()->get("extension"), "", $path);
            # absolute extend
            if ($path[0] == "/") {
                $dom = $this->caramel->lexer()->lex($path);
                $dom = $dom["dom"];
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
                $dom  = $this->caramel->lexer()->lex($path);
            }
        } else {
            # get parent file with level and names space
            $dom = $this->caramel->lexer()->lex($node->get("namespace"), $node->get("level") + 1);
        }

        # in case we still fail somehow, at least give the user an error.
        if (!$dom) {
            throw new CaramelException("Seems like the parser crashed!", $node->get("file"), $node->get("line"));
        }

        return $dom;

    }


    /**
     * extend all blocks in the current dom
     *
     * @param Dom   $dom
     * @param array $blocks
     * @return mixed
     * @throws \Exception
     */
    private function blocks($dom, $blocks)
    {

        /** @var Node $node */
        $nodes = $dom->get("nodes");
        foreach ($nodes as &$node) {
            # process children blocks first
            if ($node->has("children")) {
                $children = new Dom();
                $children->set("nodes", $node->get("children"));
                $node->set("children", $this->blocks($children, $blocks)->get("nodes"));

            }

            if ($node->get("tag.tag") == "block") {
                $name  = trim($node->get("attributes"));
                $stash = &$blocks[ $name ];
                if (!is_null($stash)) {
                    foreach ($stash as $block) {
                        $node = $this->block($node, $block);
                    }
                }
            }

        }

        $dom->set("nodes", $nodes);

        return $dom;
    }


    /**
     * inserts the node into "block parent" if available
     * and then replaces the node with the block
     *
     * @param Node $node
     * @param Node $block
     * @return Node
     */
    private function block($node, $block)
    {
        $block = $this->parent($node, $block);
        $node  = $block;

        return $node;
    }


    /**
     * @param Node $node
     * @param Node $block
     * @return mixed
     */
    private function parent($node, $block)
    {
        if ($block->has("children")) {
            foreach ($block->get("children") as $item) {
                /** @var Node $item */
                $this->parent($node, $item);
                if ($item->get("tag.tag") == "block" && $item->get("attributes") == "parent") {
                    $item->set("children", array($node));
                }
            }
        }

        return $block;
    }

}