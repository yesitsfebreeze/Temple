<?php

namespace Caramel;

/**
 * handles the directory creation
 *
 * Class DirectoryHandler
 * @package Caramel
 */
class DirectoryHandler
{

    /** @var Config $config */
    private $config;


    /**
     * DirectoryHandler constructor.
     * @param Config $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * adds a template directory
     *
     * @param $dir
     * @return mixed
     */
    public function addTemplateDir($dir)
    {

        return $this->addDirectory($dir, "templates/dirs");
    }

    /**
     * returns all or selected template directories
     *
     * @param $dir
     * @return string|array|bool
     */
    public function getTemplateDir($dir = false)
    {
        return $this->getDirectory($dir, "templates/dirs");
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function addPluginDir($dir)
    {
        return $this->addDirectory($dir, "plugins/dirs");
    }

    /**
     * returns all or selected plugin directories
     *
     * @param $dir
     * @return string|array|bool
     */
    public function getPluginDir($dir = false)
    {
        return $this->getDirectory($dir, "plugins/dirs");
    }


    /**
     * sets the cache directory
     *
     * @param $dir
     * @return bool|Error
     */
    public function setCacheDir($dir)
    {
        return $this->addDirectory($dir, "cache_dir", true);
    }

    /**
     * returns the cache directory
     *
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
     * note: the directories will be added top down,
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

            $dirs = $this->config->get($name);

            if ("array" == gettype($dirs)) {
                return $this->assignToArray($name,$dirs,$dir,$create);
            } else {
                return $this->assignToString($name,$dirs,$dir,$create);
            }

        } catch (\Exception $e) {
            return new Error($e);
        }
    }

    /**
     * @param $name
     * @param $dirs
     * @param $dir
     * @param $create
     * @return bool|string
     */
    private function assignToArray($name,$dirs,$dir,$create)
    {
        if (array_key_exists($dir, array_flip($dirs))) {
            return false;
        } else {
            if ($dir) {

                $dir = $this->getDirectoryPath($dir);
                $this->createDirectory($create,$dir);

                if (strrev($dir)[0] != "/") $dir = $dir . "/";
                array_unshift($dirs, $dir);
                $this->config->set($name, $dirs);

                return $dir;
            } else {
                return false;
            }
        }
    }

    private function assignToString($name,$dirs,$dir,$create)
    {
        $dir = $this->getDirectoryPath($dir);
        $this->createDirectory($create,$dir);
        if (strrev($dir)[0] != "/") $dir = $dir . "/";
        $this->config->set($name, $dir);

        return $dirs;
    }

    /**
     * @param boolean $create
     * @param string $dir
     */
    private function createDirectory($create, $dir)
    {
        if ($create) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }

    /**
     * returns the selected directory/ies
     *
     * @param null $dir
     * @param $name
     * @return array|bool
     */
    private function getDirectory($dir = NULL, $name)
    {
        $dirs = $this->config->get($name);
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
     * checks if we have a relative or an absolute directory
     * and returns the adjusted directory
     *
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
     * checks if the passed directory exists
     *
     * @param $dir
     * @return Error|string
     */
    private function validateDirectory($dir)
    {
        if ($dir[0] != "/") $dir = $this->getRootDirectory() . $dir . "/";
        if (is_dir($dir)) return $dir;

        return new Error("Cannot add directory because it does not exist:", $dir);
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
     * Returns the Caramel Directory
     *
     * @return array|string
     */
    private function getFrameworkDirectory()
    {

        if ($this->config->has("frameworkDir")) {
            return $this->config->get("frameworkDir");
        } else {
            $framework = explode("Caramel", __DIR__);
            $framework = $framework[0] . "Caramel/";

            return $framework;
        }
    }
}