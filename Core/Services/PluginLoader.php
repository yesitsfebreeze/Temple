<?php

namespace Caramel;

/**
 * handles the plugin loading
 *
 * Class PluginLoader
 * @package Caramel
 */
class PluginLoader
{

    /** @var Config $config */
    private $config;

    /*** @var array $plugins */
    private $plugins = array();

    /**
     * Plugins constructor.
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->caramel   = $caramel;
        $this->config    = $caramel->config();
        $this->variables = $caramel->getVariable();
        $plugins         = $this->getPlugins();
        $this->config->set("plugins.registered", $plugins);
    }

    /**
     * gets all registered plugins
     */
    private function getPlugins()
    {
        $dirs = $this->config->get("plugins.dirs");
        # iterate all plugin directories
        foreach ($dirs as $dir) {
            # search the directory recursively to get all plugins
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST);
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
        $pluginName = strrev(explode("/", strrev(str_replace(".php", "", $pluginFile)))[0]);
        $pluginName = strtoupper($pluginName[0]) . substr($pluginName, 1);

        $pluginClass = "\\" . __NAMESPACE__ . "\\" . "Plugin" . $pluginName;
        if (class_exists($pluginClass)) {
            # create a new instance of the plugin
            $plugin = new $pluginClass($this->caramel);
            # add the plugin to our plugins array
            $this->addPlugin($plugin->getPosition(), $plugin);
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