<?php

namespace Caramel;

/**
 * Class Plugins
 * @package Caramel
 */
class Plugins
{

    /** @var Config $config */
    private $config;

    /*** @var array $pluginTypes */
    private $pluginTypes = ["Identifier", "Function"];

    /**
     * Plugins constructor.
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->caramel   = $caramel;
        $this->config    = $caramel->config();
        $this->variables = $caramel->variables();
        $plugins         = $this->getPlugins();
        $this->config->set("plugins/registered", $plugins);
    }

    /**
     * gets all registered plugins
     */
    private function getPlugins()
    {
        $dirs    = $this->config->get("plugins/dirs");
        $plugins = array();
        # iterate all plugin directories
        foreach ($dirs as $dir) {
            # search the directory recursively to get all plugins
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $plugins = $this->loadPlugin($file, $plugins);
            }
        }

        return $plugins;
    }

    /**
     * @param $file
     */
    private function loadPlugin($file, $plugins)
    {
        // TODO: plugins have a double iteration?
        # only process the file if it has a php extention
        if (strrev(substr(strrev($file), 0, 4)) == ".php") {
            # add a string to the file name to ensure we have a string
            $file = $file . '';
            foreach ($this->pluginTypes as $type) {
                if (strpos($file, $type) !== false) {

                    # magic require
                    require_once $file;

                    # get the plugin name without the extension and convert first letter to uppercase
                    $pluginName = strrev(explode("/", strrev(str_replace(".php", "", $file)))[0]);
                    $pluginName = strtoupper($pluginName[0]) . substr($pluginName,1);

                    $pluginClass = "\\" . __NAMESPACE__ . "\\" . "Caramel" . $type . "" . $pluginName;
                    if (class_exists($pluginClass)) {
                        # create a new instance of the plugin
                        $plugin = new $pluginClass($this->caramel);
                        # add the plugin to our plugins array
                        $plugins = $this->addPlugin($plugins, $plugin->getPosition(), $plugin);
                    } else {
                        $pluginClass = str_replace("\\Caramel\\", "", $pluginClass);
                        new Error("You need to define the class '$pluginClass'  !", $file);
                    }
                }
            }
        }

        return $plugins;
    }

    private function addPlugin($plugins, $position, $plugin)
    {
        # create position if not already existing
        if (!isset($plugins[ $position ])) $plugins[ $position ] = array();

        # add the plugin and then
        # sort the array to keep things in order
        $plugins[ $position ][] = $plugin;
        ksort($plugins);

        return $plugins;
    }

}