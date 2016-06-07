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

    /** @var  Directories $Directories */
    protected $Directories;

    /** @var  PluginFactory $PluginFactory */
    protected $PluginFactory;


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Utilities/Config"      => "Config",
            "Utilities/Directories" => "Directories",
            "Template/Plugins/PluginFactory" => "PluginFactory"
        );
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


    /**
     * returns all registered plugins fot the instance
     *
     * @throws \Temple\Exception\TempleException
     */
    public function getPlugins()
    {
        return $this->PluginFactory->getPlugins();
    }


    public function preProcess()
    {

    }

    public function process() {

    }

    public function postProcess()
    {

    }


}
