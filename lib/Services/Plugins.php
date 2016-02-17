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

    /**
     * Plugins constructor.
     * @param Caramel $milk
     */
    public function __construct(Caramel $milk)
    {
        $this->milk      = $milk;
        $this->config    = $milk->config();
        $this->variables = $milk->variables();
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
                $file = $file . '';
                # only process the file if it has a php extention
                if (strrev(substr(strrev($file), 0, 4)) == ".php") {
                    # magic require
                    require_once $file;
                    # get the name of the plugin by
                    # replacing the extension with nothing
                    # reverse the sting and explode it by /
                    # get the first item and then reverse it back
                    $plugin      = strrev(explode("/", strrev(str_replace(".php", "", $file)))[0]);
                    $pluginClass = "\\" . __NAMESPACE__ . "\\" . "Caramel_Plugin_" . $plugin;

                    /** @var PluginBase $plugin */
                    # create a new instance of the plugin
                    if (class_exists($pluginClass)) {
                        $plugin = new $pluginClass($this->milk);
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