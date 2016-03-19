<?php

namespace Caramel;


use Caramel\Exceptions\ExceptionHandler;
use Caramel\Models\Vars;
use Caramel\Services\Cache;
use Caramel\Services\Config;
use Caramel\Services\Directories;
use Caramel\Services\Helpers;
use Caramel\Services\Lexer;
use Caramel\Services\Parser;
use Caramel\Services\Plugins;
use Caramel\Services\Template;
use Symfony\Component\Yaml\Yaml;

// composer modules
require_once "vendor/autoload.php";

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
    private $Vars;

    /** @var Config $Config */
    private $Config;

    /** @var Directories $Directories */
    private $Directories;

    /** @var Helpers $Helpers */
    private $Helpers;

    /** @var Cache $Cache */
    private $Cache;

    /** @var Plugins $Plugins */
    private $Plugins;

    /** @var Lexer $Lexer */
    private $Lexer;

    /** @var Parser $Parser */
    private $Parser;

    /** @var Template $Template */
    private $Template;


    /**
     * Caramel constructor.
     */
    function __construct()
    {
        $this->Yaml        = new Yaml();
        $this->Vars        = new Vars();
        $this->Config      = new Config();
        $this->Directories = new Directories();
        $this->Helpers     = new Helpers();
        $this->Cache       = new Cache();
        $this->Plugins     = new Plugins();
        $this->Lexer       = new Lexer();
        $this->Parser      = new Parser();
        $this->Template    = new Template();
        $this->init();
    }


    /**
     * @return Template
     */
    public function template()
    {
        return $this->Template;
    }


    /**
     * @return Vars
     */
    public function vars()
    {
        return $this->Vars;
    }


    /**
     * @return Config
     */
    public function config()
    {
        return $this->Config;
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
        $this->Config->setYaml($this->Yaml);
        $this->Config->addConfigFile(__DIR__ . "/config.yml");
        $this->Config->setDefaults(__DIR__);
    }


    /**
     * initiates the Caramel Helpers
     */
    private function initHelpers()
    {
        $this->Helpers->setTemplate($this->Template);
        $this->Helpers->setConfig($this->Config);
    }


    /**
     * initiates the Caramel Directories
     */
    private function initDirectories()
    {
        $this->Directories->setConfig($this->Config);
    }


    /**
     * initiates the Caramel Cache
     */
    private function initCache()
    {
        $this->Cache->setConfig($this->Config);
        $this->Cache->setTemplate($this->Template);
        $this->Cache->setDirectories($this->Directories);
        $this->Cache->setHelpers($this->Helpers);
    }


    /**
     * initiates the Caramel Lexer
     */
    private function initLexer()
    {
        $this->Lexer->setConfig($this->Config);
        $this->Lexer->setHelpers($this->Helpers);
    }


    /**
     * initiates the Caramel Plugins
     */
    private function initPlugins()
    {
        $this->Plugins->setYaml($this->Yaml);
        $this->Plugins->setVars($this->Vars);
        $this->Plugins->setConfig($this->Config);
        $this->Plugins->setDirectories($this->Directories);
        $this->Plugins->setHelpers($this->Helpers);
        $this->Plugins->setCache($this->Cache);
        $this->Plugins->setLexer($this->Lexer);
        $this->Plugins->setParser($this->Parser);
        $this->Plugins->setTemplate($this->Template);
        $this->Plugins->init();
    }


    /**
     * initiates the Caramel Parser
     */
    private function initParser()
    {
        $this->Parser->setConfig($this->Config);
        $this->Parser->setCache($this->Cache);
    }


    /**
     * initiates the Caramel Template
     */
    private function initTemplate()
    {
        $this->Template->setConfig($this->Config);
        $this->Template->setCache($this->Cache);
        $this->Template->setDirectories($this->Directories);
        $this->Template->setLexer($this->Lexer);
        $this->Template->setParser($this->Parser);
        $this->Template->setCaramel($this);
    }
}