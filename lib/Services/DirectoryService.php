<?php

namespace Caramel\Services;


use Caramel\Exception\CaramelException;
use Caramel\Models\ServiceModel;


/**
 * handles the directory creation
 * Class Directories
 *
 * @package Caramel
 */
class DirectoryService extends ServiceModel
{

    /**
     * validates and adds a directory to and dir array
     * note: the directories will be added top down,
     * so the last added item will be indexed with 0
     *
     * @param string $dir
     * @param string $name
     * @param bool   $create
     * @return bool
     */
    public function add($dir, $name, $create = false)
    {

        if (!$create) {
            $dir = $this->validate($dir);
        }

        $dirs = $this->get($name);
        $name = "dirs." . $name;
        $dirs = $this->addToArray($name, $dirs, $dir, $create);

        return $dirs;

    }


    /**
     * overwrites a dir string
     *
     * @param string $dir
     * @param string $name
     * @param bool   $create
     * @return bool
     */
    public function set($dir, $name, $create = false)
    {
        if (!$create) {
            $dir = $this->validate($dir);
        }
        $name = "dirs." . $name;
        $dir = $this->replaceString($name, $dir, $create);

        return $dir;
    }


    /**
     * returns the selected directory/ies
     *
     * @param      $name
     * @return array|bool
     */
    public function get($name)
    {
        $this->check($name);

        return $this->configService->get("dirs." . $name);
    }


    /**
     * iterates over all directories and checks if they are valid
     *
     * @param string $name
     */
    private function check($name)
    {
        $dirs = $this->configService->get("dirs." . $name);
        if (is_array($dirs)) {
            $this->configService->set("dirs." . $name, array());
            foreach ($dirs as $dir) {
                $this->add($dir, $name);
            }
        } else {
            $this->set($dirs, $name, true);
        }
    }


    /**
     * @param integer $pos
     * @param string  $name
     * @return bool
     */

    public function remove($pos, $name)
    {
        $dirs = $this->configService->get($name);
        if (array_key_exists($pos, $dirs)) {
            unset($dirs[ $pos ]);
        }

        return $this->configService->set("dirs." . $name, $dirs);
    }


    /**
     * @param $name
     * @param $dirs
     * @param $dir
     * @param $create
     * @return bool|string
     */
    private function addToArray($name, $dirs, $dir, $create)
    {
        if (array_key_exists($dir, array_flip($dirs))) {
            return false;
        } else {
            if ($dir) {
                $dir = $this->path($dir);
                $this->create($create, $dir);

                $temp = strrev($dir);
                if ($temp[0] != "/") $dir = $dir . "/";
                array_unshift($dirs, $dir);
                $this->configService->set($name, $dirs);

                return $dir;
            } else {
                return false;
            }
        }
    }


    private function replaceString($name, $dir, $create)
    {
        $dir = $this->path($dir);
        $this->create($create, $dir);
        $temp = strrev($dir);
        if ($temp[0] != "/") $dir = $dir . "/";
        $this->configService->set($name, $dir);

        return $dir;
    }


    /**
     * @param boolean $create
     * @param string  $dir
     * @throws CaramelException
     */
    private function create($create, $dir)
    {
        if ($create) {
            if (!is_dir($dir)) {
                if (is_writable(dirname($dir))) {
                    mkdir($dir, 0777, true);
                } else {
                    throw new CaramelException("You don't have the right permissions to write: </br>" . $dir);
                }
            }
        }
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
            $framework = $this->framework();
            $dir       = $framework . $dir . "/";
        }

        return $dir;
    }


    /**
     * checks if the passed directory exists
     *
     * @param $dir
     * @return string
     * @throws CaramelException
     */
    private function validate($dir)
    {
        if ($dir[0] != "/") $dir = $this->root() . $dir . "/";
        if (is_dir($dir)) return $dir;

        throw new CaramelException("Cannot add directory because it does not exist:", $dir);
    }


    /**
     * gets the current document root
     *
     * @return string
     */
    private function root()
    {
        $root = strrev($_SERVER["DOCUMENT_ROOT"]);
        $root = strrev(explode("/", $root[0]));
        $dir  = explode($root, __DIR__);
        $root = $dir[0] . $root . "/";

        return $root;
    }


    /**
     * Returns the Caramel Directory
     *
     * @return array|string
     */
    private function framework()
    {
        $framework = explode("Caramel", __DIR__);
        $framework = $framework[0] . "Caramel/";

        return $framework;
    }

}