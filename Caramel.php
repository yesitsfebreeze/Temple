<?php

namespace Caramel;

/**
 * Class Caramel
 * @package Caramel
 */
class Caramel
{


    /**
     * Configuration storage
     * @var Config $config
     */
    private $config;

    /**
     * Storage of all variables for the frontend
     * @var Storage $variables
     */
    private $variables;


    /**
     * Caramel constructor.
     */
    function __construct()
    {
        try {

            $this->config = new Config();
            $this->setDirs();
            $this->cache     = new Cache($this->config);
            $this->variables = new Storage();
            $this->plugins   = new Plugins($this, $this->config, $this->variables);
            $this->parser    = new Parser($this);
            $this->lexer     = new Lexer($this);
        } catch (\Exception $e) {
            new Error($e);
        }
    }


    /**
     * Renders and displays the passed file
     * @param $file
     */
    public function display($file)
    {
        if ($this->cache->isModified($file)) {
            $lexed = $this->lexer->lex($file);
            $this->parser->parse($lexed["file"], $lexed["dom"]);
        }
        include $this->cache->getCachePath($file);
    }

    /**
     * Renders and returns the passed file
     * @param $file
     * @return string
     */
    public function fetch($file)
    {
        if ($this->cache->isModified($file)) {
            $lexed = $this->lexer->lex($file);
            $this->parser->parse($lexed["file"], $lexed["dom"]);
        }

        return file_get_contents($this->cache->getCachePath($file));
    }


    /**
     * getter for the config
     * @return Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * getter for the variables
     * @return Config
     */
    public function variables()
    {
        return $this->variables;
    }

    /**
     * replaces the default debug template
     * @return Config
     */
    public function changeDebugTemplate($file)
    {
        // TODO: implement this
        return true;
    }


    /**
     * function to assign or get data to/from our variables
     * if value is not set it will return the data
     * @param $name
     * @param bool|false $value
     * @return bool|mixed
     */
    public function data($name = false, $value = NULL)
    {
        try {
            if (!is_null($value)) {
                return $this->variables->set($name, $value);
            } else {
                if ($this->variables->has($name)) {
                    return $this->variables->get($name);
                } else {
                    return false;
                }
            }
        } catch (\Exception $e) {
            return new Error($e);
        }

    }


    /**
     * function to delete stuff from the variables
     * @param bool|false $name
     * @return bool|Error
     */
    public function unsetData($name = false)
    {
        try {
            return $this->variables->set($name, NULL);
        } catch (\Exception $e) {
            return new Error($e);
        }
    }

    /**
     * initially sets the required directories
     */
    private function setDirs()
    {
        $this->config->set("frameworkDir", __DIR__ . "/");
        $this->config->setCacheDir($this->config->get("cache_dir"));
    }
}