<?php

namespace Temple\Engine\Structs;


use Temple\Engine\Cache\VariablesBaseCache;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Languages\BaseLanguage;
use Temple\Engine\Structs\Node\Node;


class Dom
{

    /** @var VariablesBaseCache $Variables */
    private $Variables;

    /** @var BaseLanguage $Language */
    private $Language;

    /** @var  string $namespace */
    private $namespace;

    /** @var int $currentLine */
    private $currentLine = 1;

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

    /** @var  Dom $parentDom */
    protected $parentDom;

    /** @var  bool $extending */
    protected $extending;


    public function __construct($namespace, $file, $templates, $level, BaseLanguage $language, Variables $variables)
    {
        $this->Variables = $variables;
        $this->Language  = $language;
        $this->setNamespace($namespace);
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
    public function setPreviousNode($node)
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
     * @param $block
     *
     * @return mixed
     * @throws Exception
     */
    public function getBlock($block)
    {
        if (isset($this->blocks[ $block ])) {
            return $this->blocks[ $block ];
        }

        throw new Exception(1, "The block %" . $block . "% doesn't exist", $this->getFile());
    }


    /**
     * @param      $block
     * @param Node $node
     *
     * @return mixed
     * @throws Exception
     */
    public function setBlock($block, $node)
    {
        if (isset($this->blocks[ $block ])) {
            return $this->blocks[ $block ] = $node;
        }

        return false;
    }


    /**
     * @param string $name
     * @param Node   $block
     */
    public function addBlock($name, $block)
    {
        $this->blocks[ $name ] = $block;
    }


    /**
     * @return Dom
     */
    public function getParentDom()
    {
        return $this->parentDom;
    }


    /**
     * @param Dom $parentDom
     */
    public function setParentDom(Dom $parentDom)
    {
        $this->parentDom = $parentDom;
    }


    /**
     * @return boolean
     */
    public function isExtending()
    {
        return $this->extending;
    }


    /**
     * @param boolean $extending
     */
    public function setExtending($extending)
    {
        $this->extending = $extending;
    }


    /**
     * @return BaseLanguage
     */
    public function getLanguage()
    {
        return $this->Language;
    }

}