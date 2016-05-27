<?php

namespace Caramel\Factories;
use Caramel\Nodes\BaseNode;

/**
 * Class configFactory
 * @package Contentmanager\Services
 */
class PluginFactory extends Factory
{

    //    /** @inheritdoc */
    public function check($class)
    {
//        $this->getClassName($class);
//        $class = '\\Caramel\\Interfaces\\' . ucfirst($class) . "Interface";
//        if (class_exists($class)) {
//            return $class;
//        } else {
//            return null;
//        }
    }


    /**
     * @param BaseNode $node
     * @return array
     */
    public function getForNode(BaseNode $node)
    {
        $plugins = array();
        $tag = $node->get("tag.tag");
        $name = $node->getName();
        echo '<code><pre>'.print_r($name,true).'</pre></code><br>';
        echo '<code><pre>'.print_r($tag,true).'</pre></code><br>';
        die();
        return array();
    }

}