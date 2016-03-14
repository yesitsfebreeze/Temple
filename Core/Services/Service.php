<?php

namespace Caramel\Services;


use Caramel\Caramel;
use Caramel\Models\Vars;

abstract class Service
{

    /** @var  Caramel $caramel */
    protected $caramel;

    /** @var Vars $vars */
    protected $vars;

    /** @var Config $config */
    protected $config;

    /** @var Directories $directories */
    protected $directories;

    /** @var  Helpers $helpers */
    protected $helpers;

    /** @var Cache $cache */
    protected $cache;

    /** @var  Plugins $plugins */
    protected $plugins;

    /** @var  Lexer $lexer */
    protected $lexer;

    /** @var  Parser $parser */
    protected $parser;

    /** @var  Template $template */
    protected $template;


    /**
     * sets $this->caramel
     *
     * @param $Caramel
     */
    public function setCaramel($Caramel)
    {
        $this->caramel = $Caramel;
    }


    /**
     * sets $this->vars
     *
     * @param $Vars
     */
    public function setVars($Vars)
    {
        $this->vars = $Vars;
    }


    /**
     * sets $this->config
     *
     * @param $Config
     */
    public function setConfig($Config)
    {
        $this->config = $Config;
    }


    /**
     * sets $this->directories
     *
     * @param $Directories
     */
    public function setDirectories($Directories)
    {
        $this->directories = $Directories;
    }


    /**
     * sets $this->helpers
     *
     * @param $Helpers
     */
    public function setHelpers($Helpers)
    {
        $this->helpers = $Helpers;
    }


    /**
     * sets $this->cache
     *
     * @param $Cache
     */
    public function setCache($Cache)
    {
        $this->cache = $Cache;
    }


    /**
     * sets $this->plugins
     *
     * @param $Plugins
     */
    public function setPlugins($Plugins)
    {
        $this->plugins = $Plugins;
    }


    /**
     * sets $this->lexer
     *
     * @param $Lexer
     */
    public function setLexer($Lexer)
    {
        $this->lexer = $Lexer;
    }


    /**
     * sets $this->parser
     *
     * @param $Parser
     */
    public function setParser($Parser)
    {
        $this->parser = $Parser;
    }


    /**
     * sets $this->template
     *
     * @param $Template
     */
    public function setTemplate($Template)
    {
        $this->template = $Template;
    }
}