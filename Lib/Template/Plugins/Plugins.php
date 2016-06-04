<?php

namespace Temple\Template\Plugins;


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


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Utilities/Config"      => "Config",
            "Utilities/Directories" => "Directories"
        );
    }


    /** @var  PluginFactory $PluginFactory */
    protected $PluginFactory;


    public function __construct(PluginFactory $PluginFactory)
    {
        $this->PluginFactory = $PluginFactory;
    }


    /**
     * @param Instance $Temple
     */
    public function setTempleInstance(Instance $Temple)
    {
        $this->Temple = $Temple;
        $this->PluginFactory->setTempleInstance($this->Temple);
    }


    /**
     * adds a plugin directory
     *
     * @param $dir
     * @return bool|mixed|string
     */
    public function addDirectory($dir)
    {
        $dir = $this->Directories->add($dir, "plugins");
        $this->initiatePlugins($dir);

        return $dir;
    }


    /**
     * removes a plugin directory on the given position
     *
     * @param $position
     * @return bool|mixed|string
     */
    public function removeDirectory($position)
    {
        return $this->Directories->add($position, "plugins");
    }


    /**
     * returns currently available plugin directories
     *
     * @return mixed
     */
    public function getDirectories()
    {
        return $this->Directories->get("plugins");
    }



    /**
     * load and install all plugins within the added directories
     * if dir is passed it will just look for plugins within this directory
     * also add $Temple to the plugin constructor so we can use it within the plugins
     *
     * @param null $dir
     * @return bool
     */
    public function initiatePlugins($dir = null)
    {
        return $this->PluginFactory->loadAll($dir);
    }


    public function process($dom)
    {
        return $dom;
    }


    public function getPlugins()
    {
        # get all registered plugins
    }


}
