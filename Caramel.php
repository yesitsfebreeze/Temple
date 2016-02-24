<?php

namespace Caramel;

require_once "autoload.php";


/**
 * the main class for the caramel template engine
 * Class Caramel
 *
 * @package Caramel
 */
class Caramel
{
    /** @var Storage $Vars */
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
        try {
            $this->Vars   = new Vars();
            $this->Directories = new Directories($this);
            $this->Config      = new Config(__DIR__);
            $this->Helpers     = new Helpers($this);
            $this->Cache       = new Cache($this);
            $this->Plugins     = new Plugins($this);
            $this->Lexer       = new Lexer($this);
            $this->Parser      = new Parser($this);
            $this->Tempalte    = new Template($this);
        } catch (\Exception $e) {
            new Error($e);
        }
    }


    /**
     * @return Template
     */
    public function template()
    {
        return $this->Tempalte;
    }


    /**
     * @return Storage
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