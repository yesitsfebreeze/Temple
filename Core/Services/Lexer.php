<?php

namespace Caramel;

/**
 * Class Lexer
 * @package Caramel
 */
class Lexer
{

    /** @var  Config $config */
    private $config;

    /** @var  Cache $cache */
    private $cache;

    /** @var  Parser $lines */
    private $parser;

    /** @var  Storage $dom */
    private $dom;

    /** @var  Storage $node */
    private $node;

    /** @var  Storage $node */
    private $prev;

    /** @var  string $file */
    private $file;

    /** @var  integer $level */
    private $level;

    /** @var  string $namespace */
    private $namespace;

    /** @var  integer $indentAmount */
    private $indentAmount;

    /** @var  string $indentChar */
    private $indentChar;

    /** @var  string $indent */
    private $lineNo;

    /**
     * Lexer constructor.
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->config = $caramel->config();
        $this->cache  = $caramel->cache;
        $this->parser = $caramel->parser;
    }


    /**
     * @param $file
     * @param int $level
     * @return array
     */
    public function lex($file, $level = 0)
    {

        # reset necessary member variables for each file
        $this->init($file, $level);

        # open the current file and process it line per line
        $handle = fopen($this->file, "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                # convert the current line to a node and add it to the dom
                $this->createNode($line);
                # update line number
            }
            $this->lineNo++;
        }
        fclose($handle);

        # create an array with the current file path and the dom
        $lexed         = array();
        $lexed["file"] = $this->file;
        $lexed["dom"]  = $this->dom;

        return $lexed;
    }

    /**
     * @param $line
     */
    private function createNode($line)
    {
        # create a new storage for the node
        $this->node = new Node();

        # get the information for our node
        $indent     = $this->getIndent($line);
        $tag        = $this->getTag($line);
        $attributes = $this->getAttributes($line, $tag);

        # add everything we need to our node
        $this->node->set("namespace", $this->namespace);
        $this->node->set("file", $this->file);
        $this->node->set("level", $this->level);
        $this->node->set("line", $this->lineNo);
        $this->node->set("plain", $line);
        $this->node->set("indent", $indent);
        $this->node->set("attributes", trim($attributes));
        $this->node->set("display", true);
        $this->node->set("plugins", true);
        $this->node->set("selfclosing", $this->isSelfClosing($this->node));
        $this->node->set("children", array());

        $this->node->set("tag/tag", $tag);
        $this->node->set("tag/opening/display", true);
        $this->node->set("tag/opening/prefix", "<");
        $this->node->set("tag/opening/tag", $tag);
        $this->node->set("tag/opening/postfix", ">");
        $this->node->set("tag/closing/display", true);
        $this->node->set("tag/closing/prefix", "</");
        $this->node->set("tag/closing/tag", $tag);
        $this->node->set("tag/closing/postfix", ">");

        # add the node to our dom
        # all the logic of children/parent behaviour happens here
        $this->createDom();

        $this->prev = $this->node;

    }

    /**
     * @throws \Exception
     */
    private function createDom()
    {
        # if we have no indent level or we are at the first line,
        # just add the line
        if ($this->node->get("indent") == 0 || $this->node->get("line") == 1) {
            # we are at top level so add self as parent
            $this->node->set("parent", $this->node);
            # set the indent to zero
            $this->node->set("indent", 0);
            # just add the node to the dom
            $this->dom[ $this->lineNo ] = $this->node;
        } else {
            # if indent is larger
            if ($this->node->get("indent") > $this->prev->get("indent")) {
                # add last node as parent
                $this->node->set("parent", $this->prev);
                # throw an error if the parent node is selfclosing
                if ($this->isSelfClosing($this->node->get("parent"))) {
                    $tag = $this->node->get("parent")->get("tag");
                    new Error("You can't have children in an $tag!", $this->file, $this->lineNo);
                } else {
                    # otherwise add it to the children of the last node
                    $this->addChildren($this->prev);
                }
            }
            # if indent is smaller
            if ($this->node->get("indent") < $this->prev->get("indent")) {
                # add the node parent to the last nodes parent
                $parent = $this->findParent();
                $this->node->set("parent", $parent);
                # add the node to the parent
                $this->addChildren($parent);
            }
            # if indent is same
            if ($this->node->get("indent") == $this->prev->get("indent")) {
                # add the node parent to the last nodes parent
                $this->node->set("parent", $this->prev->get("parent"));
                # add the node to our last nodes parent
                $this->addChildren($this->prev->get("parent"));
            }
        }
    }

    private function findParent($parent = false)
    {
        if (!$parent) {
            $parent = $this->prev->get("parent");
        } else {
            $parent = $parent->get("parent");
        }
        if ($parent->get("indent") == $this->node->get("indent")) {
            $parent = $parent->get("parent");

            return $parent;
        } else {
            return $this->findParent($parent);
        }
    }

    /**
     * @param $node
     * @throws \Exception
     */
    private function addChildren($node)
    {
        /** @var Storage $node */
        /** @var Storage $children */
        # get the selected nodes children
        $children = $node->get("children");
        # add our current node to them
        $children[] = $this->node;
        # update the children of our selected node
        $node->set("children", $children);
    }

    /**
     * @param $node
     * @return bool
     * @throws \Exception
     */
    private function isSelfClosing($node)
    {
        /** @var Storage $node */
        # check if our tag is in the self closing array set in the config
        if (in_array($node->get("tag"), $this->config->get("self_closing"))) return true;

        return false;
    }


    /**
     * @param $line
     * @return float|int|Error
     */
    private function getIndent($line)
    {
        # get tab or space whitespace form the line start
        $whitespace = substr($line, 0, strlen($line) - strlen(ltrim($line)));

        # initially set the indent variables
        if ($this->indentAmount == 0) {
            # how many chars one indent is
            $this->indentAmount = strlen($whitespace);
            # what character is used for the indent
            $this->indentChar = $whitespace[0];
        }

        # if the indent variables are set
        if ($this->indentChar != "" && $this->indentAmount != 0) {

            # divide our counted characters trough the amount
            # we used to indent in the first line
            # this should be a non decimal number
            $indent = substr_count($whitespace, $this->indentChar);
            $indent = $indent / $this->indentAmount;
            # if we have a non decimal number return how many times we indented
            if ("integer" == gettype($indent)) return $indent;

            # else throw an error since the amount of characters doesn't match
            new Error("Indent isn't matching!", $this->file, $this->lineNo);
        }

        return 0;
    }

    /**
     * @param $line
     * @return string
     */
    private function getTag($line)
    {
        # match all characters until a word boundary or space or end of the string
        preg_match("/^(.*?)(?:$| )/", trim($line), $tag);
        $tag = trim($tag[0]);

        return $tag;
    }

    /**
     * @param $line
     * @param $tag
     * @return bool|string
     */
    private function getAttributes($line, $tag)
    {
        # replace our tag in current line so we are left with the attributes
        # trim it to remove all unnecessary whitespace
        # prepend a space to prevent stitching the attributes to the tag name
        $line       = trim($line);
        $attributes = " " . str_replace($tag, "", $line);
        if (trim($attributes) == "") return "";

        return $attributes;
    }


    /**
     * @param $file
     */
    private function init($file, $level)
    {
        # set the namespace to our file name without the extension
        $this->namespace = str_replace(".mlk", "", $file);
        # this is an array of the current file
        # and the parent files if they exist
        $templateFiles = $this->templateFiles($file);
        $file          = $this->lookupFile($templateFiles, $file, $level);
        $this->file    = $file;
        # reset the dom array
        $this->dom = array();
        # initially set the line number to 1
        $this->lineNo = 1;
        # initially set the indent amount to 0
        $this->indentAmount = 0;
        # initially set the character used to indent to none
        $this->indentChar = "";
    }


    /**
     * @param $templateFiles
     * @param $file
     * @param $level
     * @return string|Error
     */
    private function lookupFile($templateFiles, $file, $level)
    {
        # don't go above the amount of our template folders
        if ($level <= sizeof($templateFiles)) {
            # see if we have the file available for the current level
            if (isset($templateFiles[ $level ])) {
                $file = $templateFiles[ $level ];
            } else {
                # otherwise check next level
                $file = $this->lookupFile($templateFiles, $file, $level + 1);
            }
        }
        # update the level
        $this->level = $level;
        # return the file if one is found
        if (!is_null($file)) return $file;

        # if not throw error
        return new Error("Can't find template file.", $this->file, $this->lineNo - 1);
    }

    /**
     * @param $file
     * @return Error|string
     * @throws \Exception
     */
    private function templateFiles($file)
    {
        # get the file extension
        # add add the config extension if it doesn't exist
        $ext       = strrev(substr(strrev($file), 0, 4));
        $configExt = '.' . $this->config->get("extension");
        if ($ext != $configExt) $file = $file . $configExt;

        $files = array();
        foreach ($this->config->getDirectoryHandler()->getTemplateDir() as $level => $templateDir) {
            # concat all template directories
            # with he passed file path
            $checkFile = $templateDir . $file;
            # add them to our array if they exist
            if (file_exists($checkFile)) $files[ $level ] = $checkFile;
        }

        # if we found some files return them
        if (sizeof($files) > 0) return $files;
        
        # otherwise throw an error
        return new Error("Can't find template file.", $file);
    }
}