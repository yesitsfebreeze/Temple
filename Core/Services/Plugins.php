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

        return $this->plugins;
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
     * @param $pluginFile
     */
    private function loadPlugin($pluginFile)
    {
        # magic require
        require_once $pluginFile;

        # get the plugin name without the extension and convert first letter to uppercase
        $pluginName = explode("/", strrev(str_replace(".php", "", $pluginFile)));
        $pluginName = strrev($pluginName [0]);
        $pluginName = strtoupper($pluginName[0]) . substr($pluginName, 1);

        $pluginClass = "Caramel\\Plugin" . $pluginName;
        if (class_exists($pluginClass)) {
            /** @var Plugin $plugin */
            # create a new instance of the plugin
            $plugin = new $pluginClass($this->caramel);
            # add the plugin to our plugins array
            $this->addPlugin($plugin->position(), $plugin);
        } else {
            $pluginClass = str_replace("\\Caramel\\", "", $pluginClass);
            new Error("You need to define the Caramel namespaced class '$pluginClass'  !", $pluginFile);
        }
    }


    /**
     * @param $position
     * @param $plugin
     * @return array
     */
    private function addPlugin($position, $plugin)
    {
        # create position if not already existing
        if (!isset($this->plugins[ $position ])) $this->plugins[ $position ] = array();

        # add the plugin and then
        # sort the array to keep things in order
        $this->plugins[ $position ][] = $plugin;
        ksort($this->plugins);

        return $this->plugins;
    }


}