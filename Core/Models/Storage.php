<?php

namespace Caramel\Models;


use Caramel\Services\Error;


/**
 * this class handles all data storage
 * deep array setters and getters are separated by "."
 * Class Storage
 *
 * @package Caramel
 */
class Storage
{

    /** @var array $storage */
    private $storage;


    /**
     * merge an array into the storage
     *
     * @param array $array
     */
    public function merge($array)
    {
        foreach ($array as $key => $val) {
            # this overrides all set keys in the config
            # with the ones from the array
            $this->storage[ $key ] = $val;
        }
    }


    /**
     * returns a value from the storage
     *
     * @param string $path
     * @return mixed
     */
    public function get($path = NULL)
    {
        try {
            return $this->getter($path);
        } catch (\Exception $e) {
            return new Error($e);
        }
    }


    /**
     * returns if the storage has the passed value
     *
     * @param string $path
     * @return array
     */
    public function has($path)
    {
        try {
            $value = $this->getter($path);

            if (gettype($value) == "array") {
                if (sizeof($value) == 0) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @param $path
     * @param $value
     * @return bool
     */
    public function set($path, $value)
    {
        try {
            $this->setter($path, $value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        $this->set($path, NULL);
    }


    /**
     * searches for an item in the current tree
     * if we pass an array it has the same behaviour
     * iterates over the array values recursively
     *
     * @param Storage      $item
     * @param array|string $attrs
     * @param string       $value
     * @return array
     */
    public function find(&$item, $attrs, $value = NULL)
    {
        $found = array();

        $this->findHelper($found, $item, $attrs, $value);
        $children = $item->get("children");
        /** @var Storage $child */
        foreach ($children as &$child) {
            $this->find($child, $attrs, $value);
            $this->findHelper($found, $child, $attrs, $value);
        }

        return $found;
    }


    /**
     * outsourcing the repeating find process
     *
     * @param array        $found
     * @param Storage      $item
     * @param array|string $attrs
     * @param string       $value
     * @return array
     */
    private function findHelper(&$found, &$item, $attrs, $value = NULL)
    {
        if (gettype($attrs) == "array") {
            foreach ($attrs as $attr => $value) {
                if ($item->has($attr)) {
                    if ($item->get($attr) == $value) {
                        array_push($found, $item);
                    }
                }
            }
        } else {
            if ($item->has($attrs)) {
                if ($item->get($attrs) == $value) {
                    array_push($found, $item);
                }
            }
        }

        return $found;
    }


    /**
     * the method to set data
     *
     * @param $path
     * @return array
     * @throws \Exception
     */
    private function getter($path)
    {
        $storage = $this->storage;
        if ($path) {
            $paths = $this->createPath($path);
            foreach ($paths as $position => $key) {
                if (!isset($storage[ $key ])) {
                    # if path is not set throw error
                    throw new \Exception("Sorry, '{$path}' is undefined!");
                } else {
                    # reference back to current key
                    $storage = $storage[ $key ];
                }
            }

            return $storage;
        } else {
            # if nothing is defined just return whole config
            return $storage;
        }
    }


    /**
     * @param $path
     * @param $value
     * @return bool
     */
    private function setter($path, $value)
    {
        $paths   = $this->createPath($path);
        $storage = &$this->storage;
        $parent  = false;
        $name    = false;
        # recursively adds the value to the array
        foreach ($paths as $position => $name) {
            if (!isset($storage[ $name ]) || "array" != gettype($storage[ $name ])) {
                # creates array if the key doesn't exist
                $storage[ $name ] = array();
            }
            # reference back to key
            $parent  = &$storage;
            $storage = &$storage[ $name ];
        }
        if ($value === NULL) {
            unset($parent[ $name ]);
        } else {
            $storage = $value;
        }

        return $storage;
    }


    /**
     * returns the path as an array
     *
     * @param $path
     * @return array|mixed
     */
    private function createPath($path)
    {
        # remove last / if existent
        $path = preg_replace('/\.$/', '', $path);
        # get path array
        $path = explode(".", $path);

        return $path;
    }

}