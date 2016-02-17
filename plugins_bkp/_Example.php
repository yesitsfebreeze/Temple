<?php

namespace Caramel;

/**
 *
 * to implement a plugin, you have to follow a name convention.
 * The File name represents the plugin class name.
 * Each Plugin class has to be prefixed with with Caramel_Plugin_
 *
 * Example given:
 *      filename = MyPlugin.php
 *      classname = Caramel_Plugin_MyPlugin
 *
 *
 * Class Caramel_Plugin_MyPlugin
 *
 * @purpose: explains how to use plugins
 * @usage: none
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_MyPlugin extends PluginBase
{

    /**
     *
     * the parsing position
     *
     * @var int $position
     */
    public $position = 0;



    /**
     * this is called before we even touch a node
     * so we can add stuff to our config etc
     * @var array $dom
     * @return array $dom
     * has to return $dom
     */
    public function preProcess($dom)
    {
        return $dom;
    }

    /**
     * processes the actual node
     * @var Storage $node
     * @return Storage $node
     * hast to return $node
     */
    public function process($node)
    {
        return $node;
    }

    /**
     * this is called after the plugins processed
     * all nodes
     * @var array $dom
     * @return array $dom
     * * has to return $dom
     */
    public function postProcess($dom)
    {
        return $dom;
    }

    /**
     * this is called after the plugins processed
     * all nodes and converted it into a html string
     * @var array $dom
     * @return array $dom
     * * has to return $dom
     */
    public function processOutput($output)
    {
        return $output;
    }


}