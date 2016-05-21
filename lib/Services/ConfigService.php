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
                }
            } else {
                throw new CaramelException('You must declare an "$config" array!', $file);
            }
        } else {
            throw new CaramelException("Can't find the config file!", $file);
        }
    }


    /**
     * initially sets the required settings
     *
     * @param string $rootDir
     */
    public function init($rootDir)
    {
        $this->set("caramel_dir", $rootDir);
        $this->set("framework_dir", $rootDir . "lib/");
        $this->set("cache_dir", $rootDir . "cache/");

        $this->addConfigFile($rootDir . "config.php");

        if (!$this->has("use_exception_handler")) {
            $this->set("exception_handler", new ExceptionHandler());
        }

    }


}