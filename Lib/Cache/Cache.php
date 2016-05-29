<?php

namespace Temple\Cache;

use Temple\Config;
use Temple\DependencyManagement\DependencyInstance;

class Cache extends DependencyInstance {


    /** @var  Config $Config */
    protected $Config;

    public function dependencies()
    {
        return array(
            "Config"
        );
    }

}