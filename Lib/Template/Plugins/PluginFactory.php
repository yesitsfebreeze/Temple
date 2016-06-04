<?php

namespace Temple\Template\Plugins;


use Temple\Instance;
use Temple\Models\Nodes\BaseNode;
use Temple\Utilities\BaseFactory;
use Temple\Utilities\Storage;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
class PluginFactory extends BaseFactory
{

    /** @var  Storage $plugins */
    private $plugins;


    /** @var  Instance $Temple */
    private $Temple;


    public function __construct()
    {
        $this->plugins = new Storage();
    }


    /**
     * @param Instance $Temple
     */
    public function setTempleInstance(Instance $Temple)
    {
        $this->Temple = $Temple;
    }


    /**
     * @param string $class
     * @return null|string
     * @throws \Temple\Exception\TempleException
     */
    public function check($class)
    {
        $this->getClassName($class);
        $class = '\\Temple\\Plugins\\' . ucfirst($class) . "Plugin";
        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }


    /**
     * @param BaseNode $node
     * @return array
     */
    public function getPluginsForNode(BaseNode $node)
    {
        $plugins = array();
        $tag     = $node->get("tag.tag");
        $name    = $node->getName();

        return array();
    }


    public function loadAll($dir = null)
    {
        debug("load");
        # load all plugins
        return true;
    }
}