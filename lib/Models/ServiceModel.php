<?php

namespace Caramel\Models;


use Caramel\Factories\NodeFactory;
use Caramel\Factories\PluginFactory;
use Caramel\Services\CacheService;
use Caramel\Services\ConfigService;
use Caramel\Services\DirectoryService;
use Caramel\Services\LexerService;
use Caramel\Services\NodeService;
use Caramel\Services\ParserService;
use Caramel\Services\PluginService;
use Caramel\Services\TemplateService;

class ServiceModel
{

    /** @var CacheService $cache */
    public $cache = NULL;

    /** @var ConfigService $config */
    public $config = NULL;

    /** @var DirectoryService $dirs */
    public $dirs = NULL;

    /** @var PluginFactory $pluginFactory */
    public $pluginFactory = NULL;

    /** @var PluginService $plugins */
    public $plugins = NULL;

    /** @var NodeFactory $nodeFactory */
    public $nodeFactory = NULL;

    /** @var TemplateService $template */
    public $template = NULL;

    /** @var LexerService $lexer */
    public $lexer = NULL;

    /** @var ParserService $parser */
    public $parser = NULL;


    /**
     * @param CacheService $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }


    /**
     * @param ConfigService $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }


    /**
     * @param DirectoryService $dirs
     */
    public function setDirectories($dirs)
    {
        $this->dirs = $dirs;
    }


    /**
     * @param PluginService $plugins
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
    }


    /**
     * @param PluginFactory $pluginFactory
     */
    public function setPluginFactory($pluginFactory)
    {
        $this->pluginFactory = $pluginFactory;
    }


    /**
     * @param NodeFactory $nodeFactory
     */
    public function setNodeFactory($nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }


    /**
     * @param TemplateService $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }


    /**
     * @param LexerService $lexer
     */
    public function setLexer($lexer)
    {
        $this->lexer = $lexer;
    }


    /**
     * @param ParserService $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }


}