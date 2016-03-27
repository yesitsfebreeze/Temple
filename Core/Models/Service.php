<?php

namespace Caramel\Models;


use Caramel\Caramel;
use Caramel\Services\Cache;
use Caramel\Services\Directories;
use Caramel\Services\Lexer;
use Caramel\Services\Parser;
use Caramel\Services\Plugins;
use Caramel\Services\Template;

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
     * @param Caramel $Caramel
     */
    public function setCaramel(Caramel $Caramel)
    {
        $this->caramel = $Caramel;
    }

    /**
     * sets $this->vars
     *
     * @param Vars $Vars
     */
    public function setVars(Vars $Vars)
    {
        $this->vars = $Vars;
    }


    /**
     * sets $this->config
     *
     * @param Config $Config
     */
    public function setConfig(Config $Config)
    {
        $this->config = $Config;
    }


    /**
     * sets $this->directories
     *
     * @param Directories $Directories
     */
    public function setDirectories(Directories $Directories)
    {
        $this->directories = $Directories;
    }



    /**
     * sets $this->cache
     *
     * @param Cache $Cache
     */
    public function setCache(Cache $Cache)
    {
        $this->cache = $Cache;
    }


    /**
     * sets $this->plugins
     *
     * @param Plugins $Plugins
     */
    public function setPlugins(Plugins $Plugins)
    {
        $this->plugins = $Plugins;
    }


    /**
     * sets $this->lexer
     *
     * @param Lexer $Lexer
     */
    public function setLexer(Lexer $Lexer)
    {
        $this->lexer = $Lexer;
    }


    /**
     * sets $this->parser
     *
     * @param Parser $Parser
     */
    public function setParser(Parser $Parser)
    {
        $this->parser = $Parser;
    }


    /**
     * sets $this->template
     *
     * @param Template $Template
     */
    public function setTemplate(Template $Template)
    {
        $this->template = $Template;
    }
}