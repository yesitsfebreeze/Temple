<?php

namespace Temple\Plugins;


use Temple\Dependency\DependencyInstance;
use Temple\Utilities\Config;

class Plugins extends DependencyInstance
{

    /** @var Config $Config */
    protected $Config;


    public function dependencies()
    {
        return array(
            "Config"
        );
    }
}