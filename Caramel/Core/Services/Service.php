<?php

namespace Caramel\Services;


use Caramel\Caramel;
use Caramel\Models\Vars;
use Symfony\Component\Yaml\Yaml;

abstract class Service
{

    /** @var  Caramel $caramel */
    protected $caramel;

    /** @var  Yaml $yaml */
    protected $yaml;

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
     * @param Caramel $Caramel
     */
    public function setCaramel(Caramel $Caramel)
    {
        $this->caramel = $Caramel;
    }


    /**
     * sets $this->yaml
     *
     * @param Yaml $Yaml
     */
    public function setYaml(Yaml $Yaml)
    {
        $this->yaml = $Yaml;
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
     * sets $this->helpers
     *
     * @param Helpers $Helpers
     */
    public function setHelpers(Helpers $Helpers)
    {
        $this->helpers = $Helpers;
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