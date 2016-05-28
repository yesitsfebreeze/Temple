<?php

namespace Temple\Services;


use Temple\Exception\TempleException;
use Temple\Exception\ExceptionHandler;
use Temple\Repositories\StorageRepository;

class ConfigService extends StorageRepository
{

    /**
     * merges a new config file into our current config
     *
     * @param $file
     * @throws TempleException
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
                throw new TempleException('You must declare an "$config" array!', $file);
            }
        } else {
            throw new TempleException("Can't find the config file!", $file);
        }
    }

}