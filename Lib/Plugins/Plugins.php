<?php

namespace Temple\Plugins;


use Temple\Dependency\DependencyInstance;
use Temple\Instance;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;


class Plugins extends DependencyInstance
{

    /** @var Instance $Temple */
    protected $Temple;

    /** @var Config $Config */
    protected $Config;

    /** @var  Directories $directories */
    protected $Directories;


    public function dependencies()
    {
        return array(
            "Utilities/Config"      => "Config",
            "Utilities/Directories" => "Directories"
        );
    }


    /**
     * @param Instance $Temple
     */
    public function setTempleInstance(Instance $Temple)
    {
        $this->Temple = $Temple;
    }


    public function addDirectory($dir)
    {
        # Directory service -> add
        # the directory service will add them into the config
        return $dir;
    }


    public function removeDirectory()
    {
        # Directory service -> add
        # the directory service will add them into the config
    }


    public function getDirectories()
    {
        # Directory service -> add
        # the directory service will add them into the config
    }


    public function initiatePlugins($dir = null)
    {
        # load and install all plugins within the added directories
        # if dir is passed it will just look for plugins within this directory
        # also add $Temple to the plugin constructor so we can use it within the plugins
    }


    public function getPlugins()
    {
        # get all registered plugins
    }


}
