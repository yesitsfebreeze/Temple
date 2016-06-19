<?php

namespace Temple\Template;


use Temple\Exception\TempleException;
use Temple\Instance;
use Temple\Models\Plugin;
use Temple\Utilities\Directories;
use Temple\Utilities\FactoryBase;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
class PluginFactory extends FactoryBase
{

    /** @var  Directories $Directories */
    protected $Directories;


    public function dependencies()
    {
        return array(
            "Utilities/Directories" => "Directories"
        );
    }


    /** @var  array $plugins */
    private $plugins = array();

    /** @var  Instance $Temple */
    private $Temple;


    /**
     * @param Instance $Temple
     */
    public function setInstance(Instance $Temple)
    {
        $this->Temple = $Temple;
    }


    /**
     * @param string $class
     * @return null|string
     * @throws \Temple\Exception\TempleException
     */
    public function check($class)
    {
        $this->getClassName($class);
        $class = '\\Temple\\Plugins\\' . ucfirst($class) . "Plugin";
        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }


    /**
     * load all plugins within a dir or all directories
     *
     * @param null $dir
     * @return bool
     * @throws TempleException
     */
    public function loadAll($dir = null)
    {
        $dirs = $this->Directories->get("plugins");
        if (!is_null($dir)) {
            if (!isset(array_flip($dirs)[ $dir ])) {
                throw new TempleException("Please add the directory to the plugins", $dir);
            }
            $dirs = array($dir);
        }

        foreach ($dirs as $dir) {
            $this->installPluginsInDir($dir);
        }

        return true;
    }


    /**
     * returns all instantiated plugins within the container
     *
     * @param string $name
     * @return array
     */
    public function getPlugin($name)
    {

        // TODO: search for plugins

        if (!isset($this->plugins[ $name ])) {
            return null;
        }

        return $this->plugins[ $name ];
    }


    /**
     * returns all instantiated plugins
     *
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }


    /**
     * returns all instantiated plugins
     *
     * @param string $type
     * @return array
     */
    public function getPluginsByType($type)
    {

        if (!isset($this->plugins[ $type ])) {
            return null;
        }

        return $this->plugins[ $type ];
    }


    /**
     * @param string $pluginDir
     */
    private function installPluginsInDir($pluginDir)
    {
        # search the directory recursively to get all plugins
        $dir   = new \RecursiveDirectoryIterator($pluginDir);
        $files = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $name = $file->getFilename();
            $full = $file->getRealPath();
            $extension = $file->getExtension();
            if ($name !== "." && $name !== ".." && !is_dir($full) && $extension == "php") {
                $this->requirePlugin($pluginDir, $file);
            }
        }
    }


    /**
     * @param string       $path
     * @param \SplFileInfo $file
     */
    private function requirePlugin($path, \SplFileInfo $file)
    {

        /** @noinspection PhpIncludeInspection */
        require_once $file->getRealPath();
        $pluginName = ucfirst(strtolower(str_replace("." . $file->getExtension(), "", $file->getFilename())));
        $this->installPlugin($path, $pluginName);
    }


    /**
     * install the plugins and register them in Temple
     *
     * @param $path
     * @param $name
     * @throws TempleException
     */
    private function installPlugin($path, $name)
    {
        $pluginName = $this->getNamespace($path, $name);
        /** @var Plugin $plugin */
        if (class_exists($pluginName)) {
            $Plugin = new $pluginName($this->Temple);
            $this->addPlugin($Plugin);
        }
    }


    /**
     * returns the correct namespace for the plugin
     *
     * @param $path
     * @param $name
     * @return string
     */
    private function getNamespace($path, $name)
    {
        $namespace      = "Temple\\Plugin\\";
        $namespaceArray = explode(".", $path);
        array_pop($namespaceArray);
        foreach ($namespaceArray as $name) {
            $namespace .= ucfirst($name) . "\\";
        }
        $namespace = $namespace . ucfirst($name);

        return $namespace;
    }


    /**
     * @param Plugin $Plugin
     * @return array
     * @throws TempleException
     */
    private function addPlugin(Plugin $Plugin)
    {

        $pluginType = $this->validatePlugin($Plugin);
        $position   = $Plugin->position();
        if (!isset($this->plugins[ $pluginType ])) {
            $this->plugins[ $pluginType ] = array();
        }

        if (!isset($this->plugins[ $pluginType ][ $position ])) {
            $this->plugins[ $pluginType ][ $position ] = array();
        }

        $this->plugins[ $pluginType ][ $position ][] = $Plugin;

        return $this->plugins;
    }


    /**
     * checks if the plugin is valid and
     * returns the plugin container type
     *
     * @param Plugin $Plugin
     * @throws TempleException
     * @return string
     */
    private function validatePlugin(Plugin $Plugin)
    {

        $name     = $Plugin->getName();
        $position = $Plugin->position();

        if (!$position) {
            throw new TempleException("Please set a position for the plugin '$name'!");
        }

        $methods = get_class_methods($Plugin);
        $types   = array();
        foreach ($methods as $method) {
            if (substr($method, 0, 2) == "is") {
                $types[ $method ] = ($Plugin->$method()) ? 1 : 0;
            }
        }

        # creates a duplicate checker for the plugin type
        $enabled = array_count_values($types);

        if (!isset($enabled[1])) {
            throw new TempleException("One plugin type method of the plugin '$name' should return true!");
        }

        if ($enabled[1] > 1) {
            throw new TempleException("Plugin '$name' has more then one type set to true!");
        }

        # returns the active container type of the plugin
        return substr(strtolower(array_flip($types)[1]), 2);
    }

}