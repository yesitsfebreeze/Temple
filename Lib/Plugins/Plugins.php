<?php

namespace Temple\Plugins;


use Temple\Dependency\DependencyInstance;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;

class Plugins extends DependencyInstance
{

    /** @var Config $Config */
    protected $Config;

    /** @var  Directories $directories */
    protected $Directories;


    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config",
            "Utilities/Directories" => "Directories"
        );
    }


    public function addDirectory($dir)
    {
        # the directory service will add them into the config
        $value = $this->Directories->add($dir, "plugins");
        # Initiate the newly added plugins
        $this->initiatePlugins($dir);

        return $value;
    }


    public function removeDirectory($dir)
    {
        return $this->Directories->remove($dir, "plugins");
        # Directory service -> remove
        # the directory service will add them into the config
    }


    public function getDirectories()
    {
        return $this->Directories->get("plugins");
        # the directory service will add them into the config
    }


    public function initiatePlugins($dir = NULL)
    {
        # load and install all plugins within the added directories
        # if dir is passed it will just look for plugins within this directory
    }


    public function getPlugins()
    {
        # get all plugins within the directories
    }


}
