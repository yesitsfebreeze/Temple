<?php

namespace Caramel\Services;


use Caramel\Models\Plugin;

/**
 * handles the plugin loading
 * Class PluginLoader
 *
 * @package Caramel
 */
class Plugins extends Service
{

    /** @var array $plugins */
    private $list;

    /** @var array $containers */
    private $containers;


    /**
     * initiates the plugins
     */
    public function init()
    {
        # add the default plugin dir
        $this->containers = $this->config->get("plugin_containers");
        $this->list = $this->loadPlugins();
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return string
     */
    public function addPluginDir($dir)
    {
        return $this->directories->add($dir, "plugins.dirs");
    }


    /**
     * removes a plugin dir
     *
     * @param integer $pos
     * @return string
     */
    public function removePluginDir($pos)
    {
        return $this->directories->remove($pos, "plugins.dirs");
    }


    /**
     * returns all plugin dirs
     *
     * @return array
     */
    public function getPluginDirs()
    {
        return $this->directories->get("plugins.dirs");
    }


    /**
     * returns all plugin dirs
     *
     * @return array
     */
    public function getPlugins()
    {
        return $this->list;
    }


    /**
     * gets all registered plugins
     */
    private function loadPlugins()
    {
        $dirs = $this->getPluginDirs();
        # iterate all plugin directories
        foreach ($dirs as $dir) {
            # search the directory recursively to get all plugins
            $dir   = new \RecursiveDirectoryIterator($dir);
            $files = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $pluginFile) {
                $this->requirePlugin($pluginFile);
            }
        }

        $this->installPlugins();

        return $this->list;
    }


    /**
     * loads a plugin via requires_one
     *
     * @param $pluginFile
     * @return mixed
     */
    private function requirePlugin($pluginFile)
    {
        # only process the file if it has a php extension
        if (strrev(substr(strrev($pluginFile), 0, 4)) == ".php") {
            # add a string to the file name to ensure we have a string
            $pluginFile = $pluginFile . '';
            if (file_exists($pluginFile)) {
                /** @noinspection PhpIncludeInspection */
                require_once $pluginFile;
            }
        }

    }


    /**
     * install the plugins and register them in caramel
     */
    private function installPlugins()
    {
        if (!array_key_exists("global", $this->containers)) {
            $this->containers[] = "global";
        }
        $plugins = $this->getNamespacedPlugins();
        foreach ($this->containers as $container) {
            foreach ($plugins as $plugin) {
                if (strtolower($plugin["container"]) == strtolower($container)) {
                    $plugin = $this->installPlugin($plugin["class"]);
                    $this->addPlugin($plugin->position(), $plugin);
                }
            }
        }
    }


    /**
     * creates a new plugin instance with the given class
     *
     * @param string $class
     * @return Plugin
     */
    private function installPlugin($class)
    {
        /** @var Plugin $plugin */
        # create a new instance of the plugin
        $plugin = new $class();


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


    /**
     * returns all Plugins within the Caramel\Plugin namespace
     */
    private function getNamespacedPlugins()
    {
        $namespace = "Caramel\\Plugins";
        $plugins   = array();
        $classes   = get_declared_classes();
        foreach ($classes as $class) {
            if (strpos($class, $namespace) !== false) {
                $plugin              = array();
                $plugin["class"]     = $class;
                $temp                = explode("\\", trim(str_replace($namespace, "", $class), "\\"));
                $plugin["container"] = $temp[0];
                $plugin["name"]      = $temp[1];
                if ($plugin["name"] == "") {
                    $plugin["name"]      = $plugin["container"];
                    $plugin["container"] = "global";
                }

                $plugins[] = $plugin;
            }
        }

        return $plugins;
    }
}