<?php

namespace Temple\Engine\Structs;


use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;


/**
 * this class handles all data storage
 * Class Storage
 *
 * @package Temple
 */
class Storage extends Injection
{

    /** @inheritdoc */
    public function dependencies()
    {
        return array();
    }


    /** @var array $storage */
    private $storage;


    /**
     * @param $path
     * @param $value
     *
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
     *
     * @return mixed
     * @throws Exception
     */
    public function get($path = null)
    {
        return $this->getter($path);
    }


    /**
     * merge an array into the storage
     *
     * @param array $array
     *
     * @throws Exception
     */
    public function merge($array)
    {
        if (!is_array($array)) {
            throw new Exception(1, "Cannot merge, no array was given!");
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
     *
     * @return array
     * @throws Exception
     */
    public function extend($path, $value)
    {

        if ($this->has($path)) {
            $temp = $this->get($path);
        } else {
            $temp = array();
        }

        if (!is_array($temp)) {
            throw new Exception(1, "Can't extend an non array value!");
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
     *
     * @return bool
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
     *
     * @return bool
     */
    public function delete($path)
    {
        $this->set($path, null);
    }


    /**
     * the method to set data
     *
     * @param $path
     *
     * @return array
     * @throws \Exception
     */
    private function getter($path)
    {
        # if nothing is defined just return whole config
        if (!$path) return $this->storage;

        $storage = $this->storage;

        $paths = $this->createPath($path);
        foreach ($paths as $position => $key) {

            if (!isset($storage[ $key ])) {
                throw new Exception(1, "Sorry, '{$path}' is undefined!");
            }

            # reference back to current key
            $storage = $storage[ $key ];

        }

        return $storage;

    }


    /**
     * @param $path
     * @param $value
     *
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
            # creates array if the key doesn't exist
            if (!isset($storage[ $name ]) || !is_array($storage[ $name ])) {
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
     *
     * @return array|mixed
     */
    private function createPath($path)
    {
        return array_values(explode(".", $path));
    }

}