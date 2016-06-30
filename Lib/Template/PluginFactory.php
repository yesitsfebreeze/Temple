<?php

namespace Shift\Template;


use Shift\Exception\ShiftException;
use Shift\Instance;
use Shift\Models\Plugin;
use Shift\Utilities\Directories;
use Shift\Utilities\FactoryBase;


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

    /** @var  Instance $Shift */
    private $Shift;


    /**
     * @param Instance $Shift
     */
    public function setInstance(Instance $Shift)
    {
        $this->Shift = $Shift;
    }


    /**
     * @param string $class
     * @return null|string
     * @throws \Shift\Exception\ShiftException
     */
    public function check($class)
    {
        $this->getClassName($class);
        $class = '\\Shift\\Plugins\\' . ucfirst($class) . "Plugin";
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
     * @throws ShiftException
     */
    public function loadAll($dir = null)
    {
        $dirs = $this->Directories->get("plugins");
        if (!is_null($dir)) {
            if (!isset(array_flip($dirs)[ $dir ])) {
                throw new ShiftException("Please add the directory to the plugins", $dir);
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
            $name      = $file->getFilename();
            $full      = $file->getRealPath();
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
     * install the plugins and register them in Shift
     *
     * @param $path
     * @param $name
     * @throws ShiftException
     */
    private function installPlugin($path, $name)
    {
        $pluginName = $this->getNamespace($path, $name);
        /** @var Plugin $plugin */
        if (class_exists($pluginName)) {
            $Plugin = new $pluginName($this->Shift);
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
        $namespace      = "Shift\\Plugin\\";
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
     * @throws ShiftException
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
     * @throws ShiftException
     * @return string
     */
    private function validatePlugin(Plugin $Plugin)
    {

        $name     = $Plugin->getName();
        $position = $Plugin->position();

        if (!$position) {
            throw new ShiftException("Please set a position for the plugin '$name'!");
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
            throw new ShiftException("One plugin type method of the plugin '$name' should return true!");
        }

        if ($enabled[1] > 1) {
            throw new ShiftException("Plugin '$name' has more then one type set to true!");
        }

        # returns the active container type of the plugin
        return lcfirst(substr(array_flip($types)[1], 2));
    }

}