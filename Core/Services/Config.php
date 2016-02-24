<?php

namespace Caramel;


/**
 * Class CaramelConfig
 *
 * @package Caramel
 */
class Config extends Storage
{

    /** @var string $root */
    private $root;


    /**
     * add default config file on construct
     *
     * @param string  $root
     */
    public function __construct($root)
    {
        $this->root = $root;
        # adding the default configuration
        $this->addConfigFile($this->root . "/Models/Config.php");
        $this->setDefaults();
    }


    /**
     * merges a new config file into our current config
     *
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
     * initially sets the required settings
     */
    private function setDefaults()
    {
        $this->set("templates.dirs", array());
        $this->set("plugins.dirs", array());
        $this->set("framework_dir", $this->root . "/");
        $this->set("cache_dir", $this->get("cache_dir"));
    }

}