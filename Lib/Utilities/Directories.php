<?php

namespace Temple\Utilities;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;


/**
 * Class Directories
 *
 * @package Temple
 */
class Directories extends DependencyInstance
{

    /** @var  Config $Config */
    protected $Config;


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config"
        );
    }


    /**
     * adds the directory into the config for the respective type
     *
     * @param $dir
     * @param $type
     * @return bool|mixed|string
     * @throws \Temple\Exception\TempleException
     */
    public function add($dir, $type)
    {
        $dir  = $this->validate($dir);
        $dirs = $this->get($type);
        $type = "dirs." . $type;
        $dir  = $this->addHandler($type, $dirs, $dir);

        return $dir;

    }


    /**
     * sets a single directory into the config for the respective type
     *
     * @param $dir
     * @param $type
     * @return string
     * @throws TempleException
     */
    public function set($dir, $type)
    {
        $dir  = $this->validate($dir);
        $type = "dirs." . $type;
        $dir  = $this->sethHandler($dir, $type);

        return $dir;
    }


    /**
     * creates a directory at the given path
     *
     * @param $dir
     * @throws \Temple\Exception\TempleException
     */
    public function create($dir)
    {
        $dir = $this->path($dir);
        if (!is_dir($dir)) {
            if (!is_writable(dirname($dir))) {
                throw new TempleException("You don't have the permission to create this directory." . $dir);
            }

            mkdir($dir, 0777, true);
        }
    }


    /**
     * removes the directory into the config for the respective type
     *
     * @param integer $level
     * @param string  $type
     * @return bool
     */
    public function remove($level, $type)
    {
        if (!$this->Config->has("dirs." . $type)) {
            return false;
        }

        $dirs = $this->Config->get("dirs" . $type);

        if (array_key_exists($level, $dirs)) {
            unset($dirs[ $level ]);
        }

        return $this->Config->set("dirs." . $type, $dirs);
    }


    /**
     * get all directories for the passed type
     *
     * @param $type
     * @return mixed
     * @throws \Temple\Exception\TempleException
     */
    public function get($type)
    {
        return $this->check($type);
    }


    /**
     * @param $name
     * @param $dirs
     * @param $dir
     * @return bool|string
     */
    private function addHandler($name, $dirs, $dir)
    {
        if (array_key_exists($dir, array_flip($dirs))) {
            return false;
        } else {
            if (!$dir) {
                return false;
            }
            $dir = $this->path($dir);

            # always add a trailing space
            if (strrev($dir)[0] != "/") {
                $dir = $dir . "/";
            }

            array_unshift($dirs, $dir);

            $this->Config->set($name, $dirs);

            return $dir;
        }
    }


    /**
     * sets a single directory in the config
     *
     * @param $type
     * @param $dir
     * @return string
     * @throws TempleException
     */
    private function sethHandler($dir, $type)
    {
        $dir  = $this->path($dir);
        $temp = strrev($dir);
        if ($temp[0] != "/") $dir = $dir . "/";
        $this->Config->set($type, $dir);

        return $dir;
    }


    /**
     * checks if we have a relative or an absolute directory
     * and returns the adjusted directory
     *
     * @param $dir
     * @return string
     */
    private function path($dir)
    {
        if ($dir[0] != "/") {
            $dir = $this->frameworkDir() . $dir . "/";
        }
        $dir = str_replace("/./", "/", $dir);

        return $dir;
    }


    /**
     * iterates over all directories and checks if they are valid
     *
     * @param string $type
     * @return mixed
     * @throws \Temple\Exception\TempleException
     */
    private function check($type)
    {

        if (!$this->Config->has("dirs." . $type)) {
            throw new TempleException("Something is wrong with the Default Config, please reset it!");
        }

        $dirs = $this->Config->get("dirs." . $type);
        if (is_array($dirs)) {
            foreach ($dirs as $dir) {
                $this->validate($dir);
            }
        } else {
            $this->validate($dirs);
        }

        return $this->Config->get("dirs." . $type);
    }


    /**
     * checks if the passed directory exists
     *
     * @param $dir
     * @return string
     * @throws TempleException
     */
    private function validate($dir)
    {
        if ($dir[0] != "/") $dir = $this->root() . $dir . "/";
        if (is_dir($dir)) return $dir;

        throw new TempleException("Cannot add directory because it does not exist, please create it.", $dir);
    }


    /**
     * gets the current document root
     *
     * @return string
     */
    private function root()
    {
        $root = $_SERVER["DOCUMENT_ROOT"] . "/";
        if ($this->Config->has("subfolder")) {
            $root = $root . $this->Config->get("subfolder") . "/";
        }
        $root = preg_replace("/\/+/", "/", $root);

        return $root;
    }


    /**
     * Returns the Temple Directory
     *
     * @return array|string
     */
    private function frameworkDir()
    {
        $framework = explode("Temple", __DIR__);
        $framework = $framework[0] . "Temple/";

        return $framework;
    }


}