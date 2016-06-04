<?php

namespace Temple\Factories;


use Temple\Models\Nodes\BaseNode;
use Temple\Utilities\BaseFactory;
use Temple\Utilities\Storage;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
class Factory extends BaseFactory
{

    /** @var  PluginInitService $plugins */
    private $plugins;


    /**
     * @param Storage $plugins
     */
    public function setPlugins(Storage $plugins)
    {
        $this->plugins = $plugins;
    }


    //    /** @inheritdoc */
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

}