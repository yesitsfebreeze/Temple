<?php

namespace Temple\Template\Plugins;


use Temple\Exception\TempleException;
use Temple\Instance;
use Temple\Models\Nodes\BaseNode;
use Temple\Models\Plugins\Plugin;
use Temple\Utilities\BaseFactory;
use Temple\Utilities\Directories;
use Temple\Utilities\Storage;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
class PluginFactory extends BaseFactory
{

    /** @var  Storage $plugins */
    private $plugins;

    /** @var  Instance $Temple */
    private $Temple;

    /** @var  Directories $Directories */
    private $Directories;


    /**
     * PluginFactory constructor.
     */
    public function __construct()
    {
        $this->plugins = new Storage();
        $this->plugins->set("functions", array());
        $this->plugins->set("plugins", array());
    }


    /**
     * @param Instance $Temple
     */
    public function setInstance(Instance $Temple)
    {
        $this->Temple = $Temple;
    }


    /**
     * @param Directories $Directories
     */
    public function setDirectories(Directories $Directories)
    {
        $this->Directories = $Directories;
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
     * @param BaseNode $node
     * @return array
     */
    public function getPluginsForNode(BaseNode $node)
    {
        $plugins = array();
        $tag     = $node->get("tag.tag");
        $name    = $node->getName();
        var_dump($tag);
        var_dump($name);
        debug();

        return array();
    }


    public function getPlugins()
    {
        return $this->plugins;
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
     * @param $pluginDir
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
            if ($name !== "." && $name !== ".." && !is_dir($full)) {
                $this->requirePlugin($pluginDir, $file);
            }
        }
    }


    /**
     * @param              $path
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
        $plugin     = new $pluginName($this->Temple);
        $this->addPlugin($plugin, $path);
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
     * @param Plugin $plugin
     * @param string $path
     * @return Storage
     */
    private function addPlugin($plugin, $path)
    {
        $position   = $plugin->position();
        $isFunction = $plugin->isFunction();
        $forTags    = $plugin->forTags();
        
        var_dump($position);
        var_dump($isFunction);
        var_dump($forTags);
        var_dump($path);
        var_dump("static functions :D");
        debug();

//        if (!is_int($position)) {
//            throw new TempleException("The plugins '" . $pluginName . "' method 'position' has to return an integer");
//        }
        
//        $this->plugins->set($position . "." . $path, $plugin);

//        return $this->plugins;
    }

}