<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;
use Caramel\Plugin\Plugin;

/**
 * handles the plugin loading
 * Class PluginLoader
 *
 * @package Caramel
 */
class Plugins extends Service
{

    /** @var  array $list */
    private $list = array();


    /**
     * initiates the plugins
     */
    public function init()
    {
        # add the default plugin dir
        $pluginDir = $this->config->get("framework_dir") . "../Plugins";
        $this->add($pluginDir);

        $registeredPlugins = $this->getPlugins();
        $this->config->set("plugins.registered", $registeredPlugins);
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return string
     */
    public function add($dir)
    {
        return $this->directories->add($dir, "plugins.dirs");
    }


    /**
     * removes a plugin dir
     *
     * @param integer $pos
     * @return string
     */
    public function remove($pos)
    {
        return $this->directories->remove($pos, "plugins.dirs");
    }


    /**
     * returns all plugin dirs
     *
     * @return array
     */
    public function dirs()
    {
        return $this->directories->get("plugins.dirs");
    }


    /**
     * adds a new container to the plugins configuration
     *
     * @param $name
     * @param $plugins
     */
    public function container($name, $plugins)
    {
        # TODO: implement this
    }


    /**
     * gets all registered plugins
     */
    private function getPlugins()
    {
        $dirs = $this->dirs();
        # iterate all plugin directories
        foreach ($dirs as $dir) {
            # search the directory recursively to get all plugins
            $dir   = new \RecursiveDirectoryIterator($dir);
            $files = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $pluginFile) {
                $this->loadPlugins($pluginFile);
            }
        }

        return $this->list;
    }


    /**
     * @param $pluginFile
     * @return mixed
     */
    private function loadPlugins($pluginFile)
    {
        # only process the file if it has a php extension
        if (strrev(substr(strrev($pluginFile), 0, 4)) == ".php") {
            # add a string to the file name to ensure we have a string
            $pluginFile = $pluginFile . '';
            $this->loadPlugin($pluginFile);
        }
    }


    /**
     * loads all plugins
     *
     * @param string $file
     * @throws CaramelException
     */
    private function loadPlugin($file)
    {
        require_once $file;
        $class = $this->getPluginName($file);
        if (class_exists($class)) {
            $plugin = $this->createPlugin($class);
            $this->addPlugin($plugin->position(), $plugin);
        } else {
            $class = str_replace("\\Caramel\\Plugin\\", "", $class);
            throw new CaramelException("You need to define the Caramel namespaced class '$class'  !", $file);
        }
    }


    /**
     * extracts the plugin name
     *
     * @param string $file
     * @return string
     */
    private function getPluginName($file)
    {
        # get the plugin name without the extension and convert first letter to uppercase
        $class = explode("/", strrev(str_replace(".php", "", $file)));
        $class = strrev($class [0]);
        $class = strtoupper($class[0]) . substr($class, 1);

        $class = "Caramel\\Plugin\\Plugin" . $class;

        return $class;
    }


    /**
     * creates a new plugin instance with the given class
     *
     * @param string $class
     * @return Plugin
     */
    private function createPlugin($class)
    {
        /** @var Plugin $plugin */
        # create a new instance of the plugin
        $plugin = new $class();


        $plugin->setYaml($this->yaml);
        $plugin->setVars($this->vars);
        $plugin->setConfig($this->config);
        $plugin->setDirectories($this->directories);
        $plugin->setHelpers($this->helpers);
        $plugin->setCache($this->cache);
        $plugin->setLexer($this->lexer);
        $plugin->setParser($this->parser);
        $plugin->setTemplate($this->template);

        return $plugin;
    }


    /**
     * @param $position
     * @param $plugin
     * @return array
     */
    private function addPlugin($position, $plugin)
    {
        # create position if not already existing
        if (!isset($this->list[ $position ])) $this->list[ $position ] = array();

        # add the plugin and then
        # sort the array to keep things in order
        $this->list[ $position ][] = $plugin;
        ksort($this->list);

        return $this->list;
    }


}