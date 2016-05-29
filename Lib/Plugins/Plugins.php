<?php

namespace Temple\Plugins;


use Temple\Utilities\Config;
use Temple\Dependency\DependencyInstance;

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