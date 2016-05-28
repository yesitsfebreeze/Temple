<?php

namespace Temple\Models;


use Temple\Repositories\ServiceRepository;
use Temple\Services\CacheService;
use Temple\Services\ConfigService;
use Temple\Services\DirectoryService;
use Temple\Services\LexerService;
use Temple\Services\ParserService;
use Temple\Services\PluginInitService;
use Temple\Services\TemplateService;


/**
 * Class Plugins
 *
 * @package Temple
 */
abstract class PluginModel
{

    /** @var ConfigService $config */
    public $config;

    /** @var CacheService $cache */
    public $cache;

    /** @var DirectoryService $directories */
    public $directories;

    /** @var PluginInitService $plugins */
    public $plugins;

    /** @var TemplateService $template */
    public $template;

    /** @var LexerService $lexer */
    public $lexer;

    /** @var ParserService $parser */
    public $parser;


    /**
     * PluginModel constructor.
     *
     * @param ServiceRepository $services
     */
    public function __construct(ServiceRepository $services)
    {
        $this->config      = $services->get("config");
        $this->cache       = $services->get("cache");
        $this->directories = $services->get("directories");
        $this->plugins     = $services->get("plugins");
        $this->template    = $services->get("template");
        $this->lexer       = $services->get("lexer");
        $this->parser      = $services->get("parser");
    }


    /**
     * @return int
     */
    abstract public function position();


    /**
     * @return string
     */
    public function getName()
    {
        return strrev(explode("\\", strrev(get_class($this))));
    }


    /**
     * this is called before we even touch a node
     * so we can add stuff to our config etc
     *
     * @var DomModel $dom
     * @return DomModel $dom
     */
    public function preProcess(DomModel $dom)
    {
        return $dom;
    }


    /**
     * the function we should use for processing a node
     *
     * @var NodeModel $node
     * @return NodeModel $node
     */
    public function process(NodeModel $node)
    {
        return $node;
    }


    /**
     * the function to check if we want to
     * modify a node
     *
     * @param $node
     * @return bool
     */
    public function check(NodeModel $node)
    {
        if ($node) ;

        return false;
    }


    /**
     * processes the actual node
     * if all requirements are met
     *
     * @var NodeModel $node
     * @return NodeModel $node
     */
    public function realProcess(NodeModel $node)
    {
        if ($this->check($node) !== false) {
            return $this->process($node);
        } else {
            return $node;
        }
    }


    /**
     * this is called after the plugins processed
     * all nodes
     *
     * @var DomModel $dom
     * @return DomModel $dom
     */
    public function postProcess(DomModel $dom)
    {
        return $dom;
    }


    /**
     * this is called after the plugins processed
     * all nodes and converted it into a html string
     *
     * @var string $output
     * @return string $output
     */
    public function processOutput($output)
    {
        return $output;
    }
}