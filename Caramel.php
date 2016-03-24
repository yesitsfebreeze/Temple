<?php

namespace Caramel;


use Caramel\Models\Vars;
use Caramel\Services\Cache;
use Caramel\Services\Config;
use Caramel\Services\Containers;
use Caramel\Services\Directories;
use Caramel\Services\Helpers;
use Caramel\Services\Lexer;
use Caramel\Services\Parser;
use Caramel\Services\Plugins;
use Caramel\Services\Template;

// Caramel loader
require_once "Autoload.php";
new Autoloader("Core", "Caramel");

/**
 * the main class for the caramel template engine
 * Class Caramel
 *
 * @package Caramel
 */
class Caramel
{
    /** @var Vars $Vars */
    private $vars;

    /** @var Config $Config */
    private $config;

    /** @var Directories $Directories */
    private $directories;

    /** @var Helpers $Helpers */
    private $helpers;

    /** @var Cache $Cache */
    private $cache;

    /** @var Plugins $Plugins */
    private $plugins;

    /** @var Lexer $Lexer */
    private $lexer;

    /** @var Parser $Parser */
    private $parser;

    /** @var Template $Template */
    private $template;


    /**
     * Caramel constructor.
     */
    function __construct()
    {
        $this->vars        = new Vars();
        $this->config      = new Config();
        $this->directories = new Directories();
        $this->helpers     = new Helpers();
        $this->cache       = new Cache();
        $this->containers   = new Containers();
        $this->plugins     = new Plugins();
        $this->lexer       = new Lexer();
        $this->parser      = new Parser();
        $this->template    = new Template();
        $this->init();
    }


    /**
     * @return Template
     */
    public function Template()
    {
        return $this->template;
    }


    /**
     * @return Vars
     */
    public function Vars()
    {
        return $this->vars;
    }


    /**
     * @return Config
     */
    public function Config()
    {
        return $this->config;
    }


    /**
     * @return Containers
     */
    public function Containers()
    {
        return $this->containers;
    }


    /**
     * initiates Caramel
     */
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
        $this->config->addConfigFile(__DIR__ . "/config.json");
        $this->config->setDefaults(__DIR__);
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
        $this->template->setCaramel($this);
        $this->template->setPlugins($this->plugins);
    }
}