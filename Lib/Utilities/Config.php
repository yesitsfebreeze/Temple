<?php

namespace Temple\Utilities;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\ExceptionHandler;
use Temple\Exception\TempleException;


class Config extends DependencyInstance
{

    /**
     * @return array
     */
    public function dependencies()
    {
        return array();
    }


    /** @var Storage $config */
    private $config;


    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->config = new Storage();
    }


    /**
     * merges a new config file into our current config
     *
     * @param $file
     * @throws TempleException
     */
    public function addConfigFile($file)
    {

        if (!file_exists($file)) {
            throw new TempleException("Can't find the config file!", $file);
        }

        /** @noinspection PhpIncludeInspection */
        require_once $file;

        if (!isset($config)) {
            throw new TempleException('You must declare an "$config" array!', $file);
        }

        if (sizeof($config) > 0) {
            $this->config->merge($config);
            if ($this->config->has("errorHandler") && $this->config->get("errorHandler")) {
                $this->config->set("errorHandler", new ExceptionHandler());
            }
        }

    }
    

    /**
     * @param null $path
     * @return mixed
     * @throws TempleException
     */
    public function get($path = null)
    {
        return $this->config->get($path);
    }


    /**
     * @param $path
     * @param $value
     * @return bool
     */
    public function set($path, $value)
    {
        return $this->config->set($path, $value);
    }


    /**
     * @param $path
     * @return array
     */
    public function has($path)
    {
        return $this->config->has($path);
    }

}