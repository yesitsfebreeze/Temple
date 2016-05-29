<?php

namespace Temple\Services;


use Temple\BaseClasses\DependencyBaseClass;
use Temple\BaseClasses\PluginBaseClass;
use Temple\Engine;
use Temple\Repositories\StorageRepository;

class PluginInitService extends DependencyBaseClass
{

    /** @var Engine $engine */
    private $engine;

    /** @var StorageRepository $plugins */
    private $plugins;


    /**
     *  initiates the plugins
     *
     * @param Engine $engine
     * @throws \Temple\Exceptions\TempleException
     */
    public function init(Engine $engine)
    {
        $this->engine  = $engine;
        $this->plugins = new StorageRepository();
        $this->loadPlugins();
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return string
     */
    public function addPluginDir($dir)
    {
        $returner = $this->directoryService->add($dir, "plugins");
        $this->loadPlugins();

        return $returner;
    }


    /**
     * removes a plugin dir
     *
     * @param integer $pos
     * @return string
     */
    public function removePluginDir($pos)
    {
        return $this->directoryService->remove($pos, "plugins");
    }


    /**
     * returns all plugin dirs
     *
     * @return array
     */
    public function getPluginDirs()
    {
        return $this->directoryService->get("plugins");
    }


    /**
     * returns all plugins
     *
     * @return StorageRepository
     */
    public function getPlugins()
    {
        return $this->plugins;
    }


    /**
     * gets all registered plugins
     */
    private function loadPlugins()
    {
        $dirs = $this->getPluginDirs();

        # iterate all plugin directories
        foreach ($dirs as $path) {

            # search the directory recursively to get all plugins
            $dir   = new \RecursiveDirectoryIterator($path);
            $files = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
            /** @var \SplFileInfo $file */
            foreach ($files as $file) {
                $name = $file->getFilename();
                $full = $file->getRealPath();
                if ($name !== "." && $name !== ".." && !is_dir($full)) {
                    $this->requirePlugin($path, $file);
                }
            }
        }


        # update the plugin list for our factory
        $this->pluginFactory->setPlugins($this->plugins);

        return $this->plugins;
    }


    /**
     * @param              $path
     * @param \SplFileInfo $file
     */
    private function requirePlugin($path, \SplFileInfo $file)
    {

        $pluginName = strtolower(str_replace("." . $file->getExtension(), "", $file->getFilename()));
        $path       = strtolower(str_replace($path, "", $file->getPath()) . "." . $pluginName);
        $pluginFile = $file->getRealPath();

        /** @noinspection PhpIncludeInspection */
        require_once $pluginFile;

        $this->installPlugin($path, $pluginName);

    }


    /**
     * install the plugins and register them in Temple
     *
     * @param $path
     * @param $pluginName
     */
    private function installPlugin($path, $pluginName)
    {
//    {


//        $plugin =
//        var_dump($pluginName);

        $namespace = $this->getNamespace($path, $pluginName);
        /** @var PluginBaseClass $plugin */
        $plugin = new $namespace($this->engine);
        $this->addPlugin($plugin->position(), $plugin, $path);
    }


    /**
     * returns the correct namepsace for the plugin
     *
     * @param $path
     * @param $pluginName
     * @return string
     */
    private function getNamespace($path, $pluginName)
    {
        $namespace      = "Temple\\Plugin\\";
        $namespaceArray = explode(".", $path);
        array_pop($namespaceArray);
        foreach ($namespaceArray as $name) {
            $namespace .= ucfirst($name) . "\\";
        }
        $namespace = $namespace . ucfirst($pluginName);

        return $namespace;
    }


    /**
     * @param int             $position
     * @param PluginBaseClass $plugin
     * @param string          $path
     * @return StorageRepository
     */
    private function addPlugin($position, $plugin, $path)
    {

//         todoo: fix order

        $this->plugins->set($position . "." . $path, $plugin);

        return $this->plugins;

    }


}