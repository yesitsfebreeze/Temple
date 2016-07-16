<?php


namespace Underware\Nodes;


use Underware\Engine\Events\Event;
use Underware\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package Underware\Nodes
 */
abstract class Node extends Event implements NodeInterface
{

    /** @var  Dom */
    protected $Dom;

    /** @var  string $namespace */
    private $namespace;

    /** @var  int $level */
    private $level;

    /** @var  int $line */
    private $line;

    /** @var  string $file */
    private $file;

    /** @var  string $relativeFile */
    private $relativeFile;

    /** @var  bool $selfClosing */
    private $selfClosing;

    /** @var  int $indent */
    private $indent;

    /** @var  Node $parent */
    private $parent;

    /** @var  array $children */
    private $children;


    /**
     * @param array $line
     *
     * @return $this
     */
    public function dispatch($line)
    {
        $this->create($line);

        return $this;
    }


    /**
     * @return Dom
     */
    public function getDom()
    {
        return $this->Dom;
    }


    /**
     * @param Dom $Dom
     *
     * @return Dom
     */
    public function setDom(Dom $Dom)
    {
        return $this->Dom = $Dom;
    }


    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }


    /**
     * @param $namespace
     *
     * @return mixed
     */
    public function setNamespace($namespace)
    {
        return $this->namespace = $namespace;
    }


    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * @param $level
     *
     * @return mixed
     */
    public function setLevel($level)
    {
        return $this->level = $level;
    }


    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }


    /**
     * @param $line
     *
     * @return mixed
     */
    public function setLine($line)
    {
        return $this->line = $line;
    }


    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param $file
     *
     * @return mixed
     */
    public function setFile($file)
    {
        return $this->file = $file;
    }


    /**
     * @return string
     */
    public function getRelativeFile()
    {
        return $this->relativeFile;
    }


    /**
     * @param $relativeFile
     *
     * @return mixed
     */
    public function setRelativeFile($relativeFile)
    {
        return $this->relativeFile = $relativeFile;
    }


    /**
     * @return boolean
     */
    public function isSelfClosing()
    {
        return $this->selfClosing;
    }


    /**
     * @param $selfClosing
     *
     * @return mixed
     */
    public function setSelfClosing($selfClosing)
    {
        return $this->selfClosing = $selfClosing;
    }


    /**
     * @return int
     */
    public function getIndent()
    {
        return $this->indent;
    }


    /**
     * @param $indent
     *
     * @return mixed
     */
    public function setIndent($indent)
    {
        return $this->indent = $indent;
    }


    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }


    /**
     * @param $parent
     *
     * @return mixed
     */
    public function setParent($parent)
    {
        return $this->parent = $parent;
    }


    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }


    /**
     * @param $children
     *
     * @return mixed
     */
    public function setChildren($children)
    {
        return $this->children = $children;
    }

}