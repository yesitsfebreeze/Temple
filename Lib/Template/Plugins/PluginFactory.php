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
        $tag      = $node->get("tag.name");
        $function = $node->isFunction();
        if ($function) {
            $allContainer = $this->plugins->get("functions.all");
            if ($this->plugins->has("functions." . $tag)) {
                $tagContainer = $this->plugins->get("functions." . $tag);
            } else {
                $tagContainer = array();
            }
        } else {
            $allContainer = $this->plugins->get("plugins.all");
            if ($this->plugins->has("plugins." . $tag)) {
                $tagContainer = $this->plugins->get("plugins." . $tag);
            } else {
                $tagContainer = array();
            }
        }

        $container = array_merge($allContainer, $tagContainer);

        return $container;
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
        $plugin = new $pluginName($this->Temple);
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
     * @throws TempleException
     */
    private function addPlugin($plugin, $path)
    {
        $container = "plugins";

        $position   = $plugin->position();
        $isFunction = $plugin->isFunction();
        $forTags    = $plugin->forTags();
        $name       = $plugin->getName();

        if (!is_int($position)) {
            throw new TempleException("Please set a position for the plugin '$name'");
        }

        if (empty($forTags)) {
            $forTags = array("all");
        }

        if ($isFunction) {
            $container = "functions";
        }


        foreach ($forTags as $tag) {
            $tagContainer = $container . "." . $tag;

            if (!$this->plugins->has($tagContainer)) {
                $this->plugins->set($tagContainer, array());
            }

            $positionContainer = $tagContainer . "." . $position;

            if (!$this->plugins->has($positionContainer)) {
                $this->plugins->set($positionContainer, array());
            }

            $endContainer   = $this->plugins->get($positionContainer);
            $endContainer[] = $plugin;
            $this->plugins->set($positionContainer, $endContainer);

            $sortContainer = $this->plugins->get($tagContainer);
            ksort($sortContainer);
            $this->plugins->set($tagContainer, $sortContainer);
        }

        return $this->plugins;
    }

}