<?php

namespace Shift\Utilities;


use Shift\Dependency\DependencyInstance;
use Shift\Exception\ExceptionHandler;
use Shift\Exception\ShiftException;


class Config extends Storage
{

    /**
     * merges a new config file into our current config
     *
     * @param $file
     * @throws ShiftException
     */
    public function addConfigFile($file)
    {

        if (!file_exists($file)) {
            throw new ShiftException("Can't find the config file!", $file);
        }

        /** @noinspection PhpIncludeInspection */
        require_once $file;

        if (!isset($config)) {
            throw new ShiftException('You must declare an "$config" array!', $file);
        }

        if (sizeof($config) > 0) {
            $this->merge($config);
            if ($this->has("errorHandler") && $this->get("errorHandler")) {
                $this->set("errorHandler", new ExceptionHandler());
            }
        }

    }

}