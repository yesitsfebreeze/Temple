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
            "Utilities/Config" => "Config",
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
        # also add $Temple to the plugin constructor so we can use it within the plugins
    }


    public function getPlugins()
    {
        # get all registered plugins
    }


}
