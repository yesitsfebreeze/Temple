<?php

namespace Caramel;

/**
 * Class CaramelConfig
 * @package Caramel
 */
class Config extends Storage
{
    /**
     * add default config file on construct
     */
    public function __construct()
    {
        $this->addConfigFile(__DIR__ . "/../../config.php");
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
        return $this->addDirectory($dir, "templates", "template");
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getTemplateDir($dir = false)
    {
        return $this->getDirectory($dir, "templates");
    }


    /**
     * @param $dir
     * @return bool|Error
     */
    public function addPluginDir($dir)
    {
        return $this->addDirectory($dir, "plugins", "plugin");
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getPluginDir($dir = false)
    {
        return $this->getDirectory($dir, "plugins");
    }


    /**
     * @param $dir
     * @return bool|Error
     */
    public function setCacheDir($dir)
    {
        return $this->addDirectory($dir, "cache", true);
    }

    /**
     * @param $dir
     * @return string|array|bool
     */
    public function getCacheDir($dir = false)
    {
        return $this->getDirectory($dir, "cache", true);
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
     * @param string $singular
     * @param bool|false $single
     * @return bool|Error
     */
    private function addDirectory($dir, $name, $singular, $single = false)
    {
        try {

            $dir = $this->validateDirectory($dir, $name, $singular);

            if ($single) {
                if ($dir) {
                    return $this->set($name . "/dir", $dir);
                }
            }

            $dirs = $this->get($name . "/dirs");
            if ("array" == gettype($dirs)) {
                if (array_key_exists($dir, array_flip($dirs))) {
                    return false;
                } else {
                    if ($dir) {
                        array_unshift($dirs, $dir);

                        return $this->set($name . "/dirs", $dirs);
                    } else {
                        return false;
                    }
                }
            } else {
                return $dirs;
            }
        } catch (\Exception $e) {
            return new Error($e);
        }
    }

    /**
     * @param null $dir
     * @param $name
     * @param bool|false $single
     * @return array|bool
     */
    private function getDirectory($dir = NULL, $name, $single = false)
    {
        if ($single) {
            return $this->get($name . "/dir");
        }
        $dirs = $this->get($name . "/dirs");
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
     * @param $path
     * @param $type
     * @return Error|string
     */
    private function validateDirectory($path, $type, $singular = false)
    {
        if ($path[0] != "/") $path = $this->getRootDirectory() . $path . "/";
        if (is_dir($path)) return $path;

        if ($singular) $type = $singular;

        return new Error("is trying to add a {$type} directory, but cant find:",$path);
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
     * assigning the default settings to the config
     */
    private function setDefaults()
    {
        $root = $this->getRootDirectory();
        # add the default cache dir
        $this->set("cache/dir", $root . 'template_cache/');
        # add default empty directories for plugins and templates
        $this->set("templates/dirs", array());
        $this->set("plugins/dirs", array());
        # add the internal plugin directory
        $this->addPluginDir(__DIR__ . '/../../Plugins/');
    }

}