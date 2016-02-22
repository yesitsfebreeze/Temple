<?php

namespace Caramel;

/**
 * Class CaramelConfig
 * @package Caramel
 */
class Config extends Storage
{

    /** @var string $root */
    private $root;

    /** @var DirectoryHandler $DirectoryHandler */
    private $DirectoryHandler;

    /**
     * add default config file on construct
     * @param $root
     */
    public function __construct($root)
    {
        $this->root = $root;
        # adding the default configuration
        $this->addConfigFile($this->root . "/Models/Config.php");
        $this->DirectoryHandler = new DirectoryHandler($this);
        $this->setDefaults();
    }

    /**
     * @param $file
     */
    public function addConfigFile($file)
    {
        if (file_exists($file)) {
            # a little magic here, which includes the given file if it exists
            include $file;

            # we always have to define the $config array
            # otherwise throw an error
            if (isset($config)) {
                $this->merge($config);
            } else {
                new Error('You need to set the array "$config"!', $file);
            }
        } else {
            new Error("Can't find the config file!", $file);
        }
    }

    /**
     * assigning the default settings to the config
     */
    private function setDefaults()
    {
        # add default empty directories for plugins and templates
        $this->set("templates/dirs", array());
        $this->set("plugins/dirs", array());
        $this->setDirectories();

    }

    /**
     * initially sets the required directories
     */
    private function setDirectories()
    {
        $this->set("frameworkDir", $this->root . "/");
        $this->set("cache_dir", $this->get("cache_dir"));
        $this->DirectoryHandler->addPluginDir(($this->root . "/Plugins"));
    }

    /**
     * @return DirectoryHandler
     */
    public function getDirectoryHandler()
    {
        return $this->DirectoryHandler;
    }

}