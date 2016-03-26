<?php

namespace Caramel\Services;


use Caramel\Caramel;
use Caramel\Models\Vars;

class Initializer
{


    public function __construct(Caramel $caramel, Vars $vars, Config $config, Directories $directories, Helpers $helpers, Cache $cache, Containers $containers, Plugins $plugins, Lexer $lexer, Parser $parser, Template $template)
    {
        $this->caramel     = $caramel;
        $this->vars        = $vars;
        $this->config      = $config;
        $this->directories = $directories;
        $this->helpers     = $helpers;
        $this->cache       = $cache;
        $this->containers  = $containers;
        $this->plugins     = $plugins;
        $this->lexer       = $lexer;
        $this->parser      = $parser;
        $this->template    = $template;

        $this->init();
    }


    private function init()
    {
        $this->initConfig();
        $this->initHelpers();
        $this->initDirectories();
        $this->initCache();
        $this->initContainer();
        $this->initPlugins();
        $this->initLexer();
        $this->initParser();
        $this->initTemplate();
    }


    /**
     * initiates the Caramel Config
     */
    private function initConfig()
    {
        $this->config->addConfigFile(__DIR__ . "/../../config.json");
        $this->config->setDefaults(__DIR__ . "/../../");
    }


    /**
     * initiates the Caramel Helpers
     */
    private function initHelpers()
    {
        $this->helpers->setTemplate($this->template);
        $this->helpers->setConfig($this->config);
    }


    /**
     * initiates the Caramel Directories
     */
    private function initDirectories()
    {
        $this->directories->setConfig($this->config);
    }


    /**
     * initiates the Caramel Cache
     */
    private function initCache()
    {
        $this->cache->setConfig($this->config);
        $this->cache->setTemplate($this->template);
        $this->cache->setDirectories($this->directories);
        $this->cache->setHelpers($this->helpers);
    }


    /**
     * initiates the Caramel Container
     */
    private function initContainer()
    {
        $this->containers->setConfig($this->config);
    }


    /**
     * initiates the Caramel Lexer
     */
    private function initLexer()
    {
        $this->lexer->setConfig($this->config);
        $this->lexer->setHelpers($this->helpers);
    }


    /**
     * initiates the Caramel Plugins
     */
    private function initPlugins()
    {
        $this->plugins->setVars($this->vars);
        $this->plugins->setConfig($this->config);
        $this->plugins->setDirectories($this->directories);
        $this->plugins->setHelpers($this->helpers);
        $this->plugins->setCache($this->cache);
        $this->plugins->setLexer($this->lexer);
        $this->plugins->setParser($this->parser);
        $this->plugins->setTemplate($this->template);
        $pluginDir = $this->config->get("framework_dir") . "../Plugins";
        $this->plugins->addPluginDir($pluginDir);
    }


    /**
     * initiates the Caramel Parser
     */
    private function initParser()
    {
        $this->parser->setConfig($this->config);
        $this->parser->setCache($this->cache);
        $this->parser->setPlugins($this->plugins);
    }


    /**
     * initiates the Caramel Template
     */
    private function initTemplate()
    {
        $this->template->setConfig($this->config);
        $this->template->setCache($this->cache);
        $this->template->setDirectories($this->directories);
        $this->template->setLexer($this->lexer);
        $this->template->setParser($this->parser);
        $this->template->setCaramel($this->caramel);
        $this->template->setPlugins($this->plugins);
    }
}