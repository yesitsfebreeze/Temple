<?php

namespace Temple\Template;

use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;

class Cache extends DependencyInstance {


    /** @var  Config $Config */
    protected $Config;

    /** @var  Directories $Directories */
    protected $Directories;

    /** @var  Template $Template */
    protected $Template;

    public function dependencies()
    {
        return array(
            "Config"
        );
    }

}