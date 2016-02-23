<?php

namespace Caramel;

/**
 *
 * Class PluginYourName
 * @package Caramel
 *
 * @purpose: handles template extending and block overriding
 * @usage: describe you plugin here
 * @author: you
 * @License: your Licence
 *
 */

class PluginYourName extends Plugin
{

    /** @var int $position */
    protected $position = 666;

    /**
     * @param Node $node
     * @return bool
     */
    private function check($node)
    {
        return ($node->get("tag/tag") == "yourtag");
    }

    /**
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        echo "your custom plugin";
        return $node;
    }

}