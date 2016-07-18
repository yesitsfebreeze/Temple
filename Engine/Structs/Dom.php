<?php

namespace Underware\Engine\Structs;


class Dom
{

    /** @var Storage $Variables */
    private $Variables;

    /** @var  string $namespace */
    private $namespace;

    /** @var int $currentLine */
    private $currentLine;

    /** @var  array $templates */
    private $templates = array();

    /** @var  int $level */
    private $level;

    /** @var  string $file */
    private $file;

    /** @var array $nodes */
    private $nodes = array();

    /** @var  Node $file */
    private $previousNode;

    /** @var  array $blocks */
    private $blocks = array();


    public function __construct($namespace, $file, $templates, $level)
    {
        $this->Variables = new Variables();
        $this->setNamespace($namespace);
        $this->setCurrentLine(0);
        $this->setTemplates($templates);
        $this->setLevel($level);
        $this->setFile($file);
    }


    /**
     * @return Storage
     */
    public function getVariables()
    {
        return $this->Variables;
    }


    /**
     * @param Storage $Variables
     */
    public function setVariables($Variables)
    {
        $this->Variables = $Variables;
    }


    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }


    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }


    /**
     * @return int
     */
    public function getCurrentLine()
    {
        return $this->currentLine;
    }


    /**
     * @param int $currentLine
     */
    public function setCurrentLine($currentLine)
    {
        $this->currentLine = $currentLine;
    }


    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }


    /**
     * @param array $templates
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;
    }


    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }


    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }


    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }


    /**
     * @param array $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }


    /**
     * @return Node
     */
    public function getPreviousNode()
    {
        return $this->previousNode;
    }


    /**
     * @param Node $node
     */
    public function setLastNode($node)
    {
        $this->previousNode = $node;
    }


    /**
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }


    /**
     * @param Node $block
     */
    public function addBlock($block)
    {
        $this->blocks[] = $block;
    }


}