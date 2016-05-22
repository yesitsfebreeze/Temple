<?php

namespace Caramel\Services;


use Caramel\Exception\CaramelException;
use Caramel\Exception\ExceptionHandler;
use Caramel\Repositories\StorageRepository;

class ConfigService extends StorageRepository
{

    /**
     * merges a new config file into our current config
     *
     * @param $file
     * @throws CaramelException
     */
    public function addConfigFile($file)
    {
        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            require_once $file;
            if (isset($config)) {
                if (sizeof($config) > 0) {
                    $this->merge($config);
                    if ($this->has("errorhandler") && $this->get("errorhandler")) {
                        $this->set("errorhandler", new ExceptionHandler());
                    }
                }
            } else {
                throw new CaramelException('You must declare an "$config" array!', $file);
            }
        } else {
            throw new CaramelException("Can't find the config file!", $file);
        }
    }

}