<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;
use Caramel\Models\Storage;

/**
 * Class CaramelConfig
 *
 * @package Caramel
 */
class Config extends Storage
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
            # a little magic here, which includes the given file if it exists
            $config = json_decode(file_get_contents($file), true);

            if (sizeof($config) > 0) {
                $this->merge($config);
            }
        } else {
            throw new CaramelException("Can't find the config file!", $file);
        }
    }


    /**
     * initially sets the required settings
     *
     * @param string $root
     */
    public function setDefaults($root)
    {
        $this->set("templates.dirs", array());
        $this->set("plugins.dirs", array());
        $this->set("framework_dir", $root . "/Core/");
        $this->set("cache_dir", $this->get("cache_dir"));
    }

}