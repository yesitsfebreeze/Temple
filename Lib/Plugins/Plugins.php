<?php

namespace Temple\Plugins;


use Temple\Config;
use Temple\DependencyManagement\DependencyInstance;

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