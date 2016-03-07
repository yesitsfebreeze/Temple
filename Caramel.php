<?php

namespace Caramel;


use Caramel\Models\Vars;
use Caramel\Services\Cache;
use Caramel\Services\Config;
use Caramel\Services\Directories;
use Caramel\Services\Helpers;
use Caramel\Services\Lexer;
use Caramel\Services\Parser;
use Caramel\Services\Plugins;
use Caramel\Services\Template;

require_once "autoload.php";

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


    /**
     * Caramel constructor.
     */
    function __construct()
    {
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
     * initiates Caramel
     */
    private function init()
    {
        $this->config()->addConfigFile(__DIR__ . "/config.json");
        $this->config()->setDefaults(__DIR__);

        $this->helpers()->setTemplate($this->template());
        $this->helpers()->setConfig($this->config());

        $this->directories()->setConfig($this->config());

        $this->cache()->setConfig($this->config());
        $this->cache()->setTemplate($this->template());
        $this->cache()->setDirectories($this->directories());
        $this->cache()->setHelpers($this->helpers());

        $this->plugins()->setDirectories($this->directories());
        $this->plugins()->setConfig($this->config());
        $this->plugins()->setCaramel($this);
        $this->plugins()->init();

        $this->lexer()->setConfig($this->config());
        $this->lexer()->setHelpers($this->helpers());

        $this->parser()->setConfig($this->config());
        $this->parser()->setCache($this->cache());

        $this->template()->setConfig($this->config());
        $this->template()->setCache($this->cache());
        $this->template()->setDirectories($this->directories());
        $this->template()->setLexer($this->lexer());
        $this->template()->setParser($this->parser());
        $this->template()->setCaramel($this);
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
     * @return Directories
     */
    public function directories()
    {
        return $this->Directories;
    }


    /**
     * @return Helpers
     */
    public function helpers()
    {
        return $this->Helpers;
    }


    /**
     * @return Cache
     */
    public function cache()
    {
        return $this->Cache;
    }


    /**
     * @return Plugins
     */
    public function plugins()
    {
        return $this->Plugins;
    }


    /**
     * @return Lexer
     */
    public function lexer()
    {
        return $this->Lexer;
    }


    /**
     * @return Parser
     */
    public function parser()
    {
        return $this->Parser;
    }

}