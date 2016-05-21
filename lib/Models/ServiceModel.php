<?php

namespace Caramel\Models;


use Caramel\Services\CacheService;
use Caramel\Services\ConfigService;
use Caramel\Services\DirectoryService;
use Caramel\Services\LexerService;
use Caramel\Services\ParserService;
use Caramel\Services\PluginService;
use Caramel\Services\TemplateService;

class ServiceModel
{

    /** @var CacheService $cache */
    public $cache = NULL;

    /** @var ConfigService $config */
    public $config = NULL;

    /** @var DirectoryService $directories */
    public $directories = NULL;

    /** @var PluginService $plugins */
    public $plugins = NULL;

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
     * @param DirectoryService $directories
     */
    public function setDirectories($directories)
    {
        $this->directories = $directories;
    }


    /**
     * @param PluginService $plugins
     */
    public function setPlugins($plugins)
    {
        $this->plugins = $plugins;
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