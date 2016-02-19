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

    /**
     * add default config file on construct
     * @param $root
     */
    public function __construct($root)
    {
        $this->root = $root;
        $this->addConfigFile(__DIR__ . "/../../default_config.php");
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
                $this->setDefaults();
            } else {
                new Error('You need to set the array "$config"!', $file);
            }
        } else {
            new Error("Can't find the config file!", $file);
        }
    }

    /**
     * @param $dir
     * @return mixed
     */
    public function addTemplateDir($dir)
    {

        return $this->addDirectory($dir, "templates/dirs");
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getTemplateDir($dir = false)
    {
        return $this->getDirectory($dir, "templates/dirs");
    }


    /**
     * @param $dir
     * @return bool|Error
     */
    public function addPluginDir($dir)
    {
        return $this->addDirectory($dir, "plugins/dirs");
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getPluginDir($dir = false)
    {
        return $this->getDirectory($dir, "plugins/dirs");
    }


    /**
     * @param $dir
     * @return bool|Error
     */
    public function setCacheDir($dir)
    {
        return $this->addDirectory($dir, "cache_dir", true);
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getCacheDir($dir = false)
    {
        return $this->getDirectory($dir, "cache_dir");
    }

    /**
     *
     * validates and adds a directory to our config
     * the $name variable will determent the array name
     * the $single variable will create a simple string instead of an array
     *
     * notice: the directories will be added top down,
     * so the last added item will be indexed with 0
     *
     * @param string $dir
     * @param string $name
     * @param bool $create
     * @return bool|Error
     */

    private function addDirectory($dir, $name, $create = false)
    {
        try {
            if (!$create) {
                $dir = $this->validateDirectory($dir);
            }

            $dirs = $this->get($name);
            if ("array" == gettype($dirs)) {
                if (array_key_exists($dir, array_flip($dirs))) {
                    return false;
                } else {
                    if ($dir) {

                        $dir = $this->getDirectoryPath($dir);

                        if ($create) {
                            if (!is_dir($dir)) {
                                mkdir($dir, 0777, true);
                            }
                        }

                        if (strrev($dir)[0] != "/") $dir = $dir . "/";
                        array_unshift($dirs, $dir);
                        $this->set($name, $dirs);

                        return $dir;
                    } else {
                        return false;
                    }
                }
            } else {

                $dir = $this->getDirectoryPath($dir);
                if ($create) {
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                }
                if (strrev($dir)[0] != "/") $dir = $dir . "/";
                $this->set($name, $dir);

                return $dirs;
            }

        } catch (\Exception $e) {
            return new Error($e);
        }
    }

    /**
     * @param null $dir
     * @param $name
     * @return array|bool
     */
    private function getDirectory($dir = NULL, $name)
    {
        $dirs = $this->get($name);
        if ($dir) {
            if (array_key_exists($dir, $dirs)) {
                return $dirs[ $dir ];
            } else {
                return false;
            }
        } else {
            return $dirs;
        }
    }

    /**
     * @param $dir
     * @return string
     */
    private function getDirectoryPath($dir)
    {
        if ($dir[0] != "/") {
            $framework = $this->getFrameworkDirectory();
            $dir       = $framework . $dir . "/";
        }
        return $dir;
    }


    /**
     * @param $path
     * @return Error|string
     */
    private function validateDirectory($path)
    {
        if ($path[0] != "/") $path = $this->getRootDirectory() . $path . "/";
        if (is_dir($path)) return $path;

        return new Error("Cannot add directory because it does not exist:", $path);
    }

    /**
     * gets the current document root
     * @return string
     */
    private function getRootDirectory()
    {
        $root = strrev(explode("/", strrev($_SERVER["DOCUMENT_ROOT"]))[0]);
        $root = explode($root, __DIR__)[0] . $root . "/";

        return $root;
    }

    /**
     * @return array|string
     */
    private function getFrameworkDirectory()
    {

        if ($this->has("frameworkDir")) {
            return $this->get("frameworkDir");
        } else {
            $framework = explode("Caramel", __DIR__);
            $framework = $framework[0] . "Caramel/";

            return $framework;
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
        # add the internal plugin directory
        $this->addPluginDir(__DIR__ . '/../Plugins/');
        $this->setDirs();

    }

    /**
     * initially sets the required directories
     */
    private function setDirs()
    {
        $this->set("frameworkDir", $this->root . "/");
        $this->setCacheDir($this->get("cache_dir"));
    }

}