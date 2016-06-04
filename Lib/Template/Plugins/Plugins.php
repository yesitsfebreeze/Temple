<?php

namespace Temple\Template\Plugins;


use Temple\Dependency\DependencyInstance;
use Temple\Instance;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;
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
    public $PluginFactory;


    public function __construct(PluginFactory $PluginFactory)
    {
        $this->PluginFactory = $PluginFactory;
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


    /**
     * process the dom with all plugins
     *
     * @param $dom
     * @return Dom
     */
    public function process(Dom $dom)
    {
        if (!$dom->has("nodes")) {
            return $dom;
        }

        $this->processNodes($dom->get("nodes"));

        return $dom;
    }


    /**
     * iterate over all nodes and its children
     *
     * @param $nodes
     * @throws \Temple\Exception\TempleException
     */
    private function processNodes($nodes)
    {

        if (is_array($nodes)) {
            /** @var BaseNode $node */
            foreach ($nodes as $node) {
                if ($node->has("children")) {
                    $children = $node->get("children");
                    $this->processNodes($children);
                }
                $node = $this->processNode($node);
            }
        }
    }


    /**
     * process a single node via plugin factory
     *
     * @param BaseNode $node
     * @return BaseNode
     */
    private function processNode(BaseNode $node)
    {
        $plugins = $this->PluginFactory->getPluginsForNode($node);
        var_dump($plugins);
        return $node;
    }


}
