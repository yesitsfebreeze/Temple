<?php

namespace Temple\Utilities;


use Temple\Exception\TempleException;


/**
 * this class handles all data storage
 * Class Storage
 *
 * @package Temple
 */
class Storage
{

    /** @var array $storage */
    private $storage;


    /**
     * @param $path
     * @param $value
     * @return bool
     */
    public function set($path, $value)
    {
        try {
            return $this->setter($path, $value);
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * returns a value from the storage
     *
     * @param string $path
     * @return mixed
     * @throws TempleException
     */
    public function get($path = null)
    {
        try {
            return $this->getter($path);
        } catch (\Exception $e) {
            throw new TempleException($e);
        }
    }


    /**
     * merge an array into the storage
     *
     * @param array $array
     * @throws TempleException
     */
    public function merge($array)
    {
        if (!is_array($array)) {
            throw new TempleException("Cannot merge, no array was given!");
        }

        foreach ($array as $key => $val) {
            # this overrides all set keys in the config
            # with the ones from the array
            $this->storage[ $key ] = $val;
        }

    }


    /**
     * extends an array in the storage
     *
     * @param string       $path
     * @param array|string $value
     * @return array
     * @throws TempleException
     */
    public function extend($path, $value)
    {

        if ($this->has($path)) {
            $temp = $this->get($path);
        } else {
            $temp = array();
        }

        if (!is_array($temp)) {
            throw new TempleException("Can't extend an non array value!");
        }

        if (is_array($value)) {
            $value = array_merge($temp, $value);
        } else {
            $value = array_merge($temp, array($value));
        }
        $this->set($path, $value);

        return $value;
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

            $this->getter($path);

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * removes the passed path form the storage
     *
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        $this->set($path, null);
    }


    // TODO: needs to be generalized not only "children"
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
    public function find($attrs, $value = null, &$item = null)
    {
        $found    = array();
        $children = false;
        $this->findHelper($found, $item, $attrs, $value);
        if ($item) {
            if ($item->has("children")) {
                $children = $item->get("children");
            }
        } else {
            if ($this->has("children")) {
                $children = $this->get("children");
            }
        }
        if ($children) {
            /** @var Storage $child */
            foreach ($children as &$child) {
                $this->find($attrs, $value, $child);
                $this->findHelper($found, $child, $attrs, $value);
            }
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
    private function findHelper(&$found, &$item, $attrs, $value = null)
    {
        if (is_array($attrs)) {
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
        if (!$path) {
            # if nothing is defined just return whole config
            return $storage;
        }

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
            if (!isset($storage[ $name ]) || !is_array($storage[ $name ])) {
                # creates array if the key doesn't exist
                $storage[ $name ] = array();
            }
            # reference back to key
            $parent  = &$storage;
            $storage = &$storage[ $name ];
        }
        if ($value === null) {
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
        return array_values(explode(".", $path));
    }

}