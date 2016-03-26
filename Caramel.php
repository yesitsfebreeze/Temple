<?php

namespace Caramel;


use Caramel\Models\Vars;
use Caramel\Services\Autoloader;
use Caramel\Services\Cache;
use Caramel\Services\Config;
use Caramel\Services\Containers;
use Caramel\Services\Directories;
use Caramel\Services\Helpers;
use Caramel\Services\Initializer;
use Caramel\Services\Lexer;
use Caramel\Services\Parser;
use Caramel\Services\Plugins;
use Caramel\Services\Template;

// Caramel loader
require_once "Core/Services/Autoload.php";
new Autoloader();

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
        $this->containers  = new Containers();
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
     * initiates^ the caramel services
     */
    private function init()
    {
        $initializer = new Initializer();
        $initializer->initConfig($this->config, __DIR__);
        $initializer->initHelpers($this->helpers, $this->template, $this->config);
        $initializer->initDirectories($this->directories, $this->config);
        $initializer->initCache($this->cache, $this->config, $this->template, $this->directories, $this->helpers);
        $initializer->initContainers($this->containers, $this->config);
        $initializer->initPlugins($this->plugins, $this->vars, $this->config, $this->directories, $this->helpers, $this->cache, $this->lexer, $this->parser, $this->template);
        $initializer->initLexer($this->lexer, $this->config, $this->helpers);
        $initializer->initParser($this->parser, $this->config, $this->cache, $this->plugins);
        $initializer->initTemplate($this->template, $this->config, $this->cache, $this->directories, $this->lexer, $this->parser, $this, $this->plugins);
    }

}