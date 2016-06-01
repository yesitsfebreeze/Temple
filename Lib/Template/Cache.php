<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;

class Cache extends DependencyInstance
{


    /** @var  Config $Config */
    protected $Config;

    /** @var  Directories $Directories */
    protected $Directories;

    /** @var  Template $Template */
    protected $Template;


    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config"
        );
    }


    public function isModified($file)
    {
        # returns if the file passed is newer than the cached file
        # we might have to pass template since we don't know if a file extends or not
    }

    public function generate()
    {
        # compile all templates to cache
    }

    public function clear()
    {
        # removes the whole cache diretory
    }


}