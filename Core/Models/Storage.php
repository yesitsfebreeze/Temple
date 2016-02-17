<?php

namespace Caramel;

use Exception as namespacedException;


/**
 * Class Storage
 * @package Caramel
 */
class Storage
{

    /** @var array $storage */
    private $storage;

    /**
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
     * @param string $path
     * @return array
     * @throws namespacedException
     */
    public function get($path = NULL)
    {
        try {
            return $this->getter($path);
        } catch (namespacedException $e) {
            return new Error($e);
        }
    }

    /**
     * @param string $path
     * @return array
     * @throws Exception
     */
    public function has($path)
    {
        try {
            $this->getter($path);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param $path
     * @return array
     * @throws namespacedException
     */
    private function getter($path)
    {
        $storage = $this->storage;
        if ($path) {
            $paths = $this->getPath($path);
            foreach ($paths as $position => $key) {
                if (!isset($storage[ $key ])) {
                    # if path is not set throw error
                    throw new namespacedException("Sorry, '{$path}' is undefined!");
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
    public function set($path, $value)
    {
        try {
            $this->setter($path, $value);

            return true;
        } catch (namespacedException $e) {
            return false;
        }
    }

    /**
     * @param $path
     * @param $value
     * @return bool
     */
    public function setter($path, $value)
    {
        $paths   = $this->getPath($path);
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
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        $this->set($path, NULL);
    }


    /**
     * @param $path
     * @return array|mixed
     */
    private function getPath($path)
    {
        #remove last / if existent
        $path = preg_replace('/\/$/', '', $path);
        # get path array
        $path = explode("/", $path);

        return $path;
    }

}