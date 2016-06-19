<?php

namespace Temple\Utilities;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\ExceptionHandler;
use Temple\Exception\TempleException;


class Config extends Storage
{

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
            $this->merge($config);
            if ($this->has("errorHandler") && $this->get("errorHandler")) {
                $this->set("errorHandler", new ExceptionHandler());
            }
        }

    }

}