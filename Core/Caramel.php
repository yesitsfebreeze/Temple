<?php

namespace Caramel;

/**
 * the main class for the caramel template engine
 *
 * Class Caramel
 * @package Caramel
 */
class Caramel
{


    /**
     * Configuration Storage
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
            $this->variables = new Storage();
            $this->config    = new Config(__DIR__ . "/..");
            $this->cache     = new Cache($this);
            $this->plugins   = new PluginLoader($this);
            $this->parser    = new Parser($this);
            $this->lexer     = new Lexer($this);
        } catch (\Exception $e) {
            new Error($e);
        }
    }


    /**
     * Renders and includes the passed file
     *
     * @param $file
     */
    public function display($file)
    {
        $file = $this->parse($file);
        include $file;
    }

    /**
     * Renders and returns the passed file
     *
     * @param $file
     * @return string
     */
    public function fetch($file)
    {
        $file = $this->parse($file);

        return file_get_contents($file);
    }


    /**
     * parsed a template file
     *
     * @param $file
     * @return mixed|string
     */
    private function parse($file)
    {
        if ($this->cache->isModified($file)) {
            $lexed = $this->lexer->lex($file);
            $this->parser->parse($lexed["file"], $lexed["dom"]);
        }

        return $this->cache->getCachePath($file);
    }

    /**
     * getter for the config
     *
     * @return Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * getter for the variables
     *
     * @param $name
     * @return Config
     */
    public function getVariables($name = NULL)
    {
        try {
            if (!is_null($name)) {
                if ($this->variables->has($name)) {
                    return $this->variables->get($name);
                } else {
                    return false;
                }
            } else {
                return $this->variables;
            }
        } catch (\Exception $e) {
            return new Error($e);
        }
    }


    /**
     * getter for the variables
     *
     * @param $name
     * @param bool|false $value
     * @return Config
     */
    public function setVariable($name = NULL, $value = NULL)
    {
        try {
            if (!is_null($value)) {
                return $this->variables->set($name, $value);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return new Error($e);
        }
    }


    /**
     * getter for the variables
     *
     * @param $name
     * @return Config
     */
    public function unsetVariable($name = NULL)
    {
        try {
            if (!is_null($name)) {
                return $this->variables->set($name, NULL);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return new Error($e);
        }
    }




    /**
     * replaces the default debug template
     *
     * @return Config
     */
    public function changeDebugTemplate($file)
    {
        // TODO: implement this
        return true;
    }


    # directory setters/getters

    /**
     * adds a new plugin directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function addPluginDir($dir)
    {
        return $this->config()->getDirectoryHandler()->addPluginDir($dir);
    }

    /**
     *  get specific or all plugin directories
     *
     * @param $dir
     * @return array|bool|string
     */
    public function getPluginDir($dir)
    {
        return $this->config()->getDirectoryHandler()->getPluginDir($dir);
    }

    /**
     * adds a new template directory
     *
     * @param $dir
     * @return mixed
     */
    public function addTemplateDir($dir)
    {
        return $this->config()->getDirectoryHandler()->addTemplateDir($dir);
    }

    /**
     * get specific or all template directories
     *
     * @param $dir
     * @return array|bool|string
     */
    public function getTemplateDir($dir)
    {
        return $this->config()->getDirectoryHandler()->getTemplateDir($dir);
    }

    /**
     * sets the cache directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function setCacheDir($dir)
    {
        return $this->config()->getDirectoryHandler()->setCacheDir($dir);
    }

    /**
     * returns the current cache directory
     *
     * @param $dir
     * @return array|bool|string
     */
    public function getCacheDir($dir)
    {
        return $this->config()->getDirectoryHandler()->getCacheDir($dir);
    }

}