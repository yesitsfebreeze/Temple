<?php

namespace Pavel\Utilities;


use Pavel\Exception\Exception;
use Pavel\Exception\ExceptionHandler;


class Config extends Storage
{

    /**
     * merges a new config file into our current config
     *
     * @param $file
     *
     * @throws Exception
     */
    public function addConfigFile($file)
    {

        if (!file_exists($file)) {
            throw new Exception("Can't find the config file!", $file);
        }

        /** @noinspection PhpIncludeInspection */
        require_once $file;

        if (!isset($config)) {
            throw new Exception('You must declare an "$config" array!', $file);
        }

        if (sizeof($config) > 0) {
            $this->merge($config);
            if ($this->has("errorHandler") && $this->get("errorHandler")) {
                $this->set("errorHandler", new ExceptionHandler());
            }
        }

    }

}