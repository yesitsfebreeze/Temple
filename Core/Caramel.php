<?php

namespace Caramel;


/**
 * the main class for the caramel template engine
 * Class Caramel
 *
 * @package Caramel
 */
class Caramel
{


    /**
     * Configuration Storage
     *
     * @var Config $config
     */
    private $config;

    /**
     * Storage of all variables for the frontend
     *
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
            $this->variables->set("caramel", $this);
            $this->config  = new Config(__DIR__);
            $this->helpers = new Helpers($this);
            $this->cache   = new Cache($this);
            $this->plugins = new PluginLoader($this);
            $this->lexer   = new Lexer($this);
            $this->parser  = new Parser($this);
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
        $templateFile = $this->parse($file);
        if ($this->config->get("file_header")) {
            echo "<!-- " . $this->config->get("file_header") . " -->";
        }
        # scoped Caramel
        $__CRML = $this;
        include $templateFile;
    }


    /**
     * Renders and returns the passed file
     *
     * @param $file
     * @return string
     */
    public function fetch($file)
    {
        $templateFile      = $this->parse($file);
        $return            = array();
        $return["file"]    = $templateFile;
        $return["content"] = file_get_contents($templateFile);

        return $return;
    }


    /**
     * parsed a template file
     *
     * @param $file
     * @return mixed|string
     */
    public function parse($file)
    {
        if ($this->cache->modified($file)) {
            $lexed = $this->lexer->lex($file);
            $this->parser->parse($lexed["file"], $lexed["dom"]);
        }

        return $this->cache->getPath($file);
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
     * getter for the variable
     * if name is not set it will return all variables
     *
     * @param $name
     * @return Config
     */
    public function getVariable($name = NULL)
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
     * @param            $name
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


    /**
     * clears the cache
     */
    public function clearCache()
    {
        return $this->cache->clear();
    }


    /**
     * returns an ordered plugin list
     *
     * @return array
     */
    public function getPlugins()
    {
        $plugins = $this->config()->get("plugins/registered");
        $list    = array();
        if (sizeof($plugins) > 0) {
            foreach ($plugins as $pluginList) {
                foreach ($pluginList as $plugin) {
                    array_push($list, $plugin->getName());
                }
            }

            return $list;
        } else {
            return array();
        }
    }


    /**
     * adds a new plugin directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function addPluginDir($dir)
    {
        return $this->config()->directories()->addPluginDir($dir);
    }


    /**
     *  get specific plugin directory
     *
     * @param $dir
     * @return array|bool|string
     */
    public function getPluginDir($dir = false)
    {
        return $this->config()->directories()->getPluginDir($dir);
    }


    /**
     *  get all plugin directories
     *
     * @return array|bool|string
     */
    public function getPluginDirs()
    {
        return $this->config()->directories()->getPluginDirs();
    }


    /**
     * adds a new template directory
     *
     * @param $dir
     * @return mixed
     */
    public function addTemplateDir($dir)
    {
        return $this->config()->directories()->addTemplateDir($dir);
    }


    /**
     * get specific template directory
     *
     * @param $dir
     * @return array|bool|string
     */
    public function getTemplateDir($dir = false)
    {
        return $this->config()->directories()->getTemplateDir($dir);
    }


    /**
     * get all template directories
     *
     * @return array|bool|string
     */
    public function getTemplateDirs()
    {
        return $this->config()->directories()->getTemplateDirs();
    }


    /**
     * sets the cache directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function setCacheDir($dir)
    {
        return $this->config()->directories()->setCacheDir($dir);
    }


    /**
     * returns the current cache directory
     *
     * @return array|bool|string
     */
    public function getCacheDir()
    {
        return $this->config()->directories()->getCacheDir();
    }

}