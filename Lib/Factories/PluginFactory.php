<?php

namespace Temple\Factories;


use Temple\Nodes\BaseNode;
use Temple\Repositories\StorageRepository;
use Temple\Services\PluginInitService;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
class PluginFactory extends Factory
{

    /** @var  PluginInitService $plugins */
    private $plugins;


    /**
     * @param StorageRepository $plugins
     */
    public function setPlugins(StorageRepository $plugins)
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