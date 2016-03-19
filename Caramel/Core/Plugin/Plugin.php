<?php

namespace Caramel\Plugins;


use Caramel\Models\Dom;
use Caramel\Models\Node;
use Caramel\Services\Service;


/**
 * Class Plugins
 *
 * @package Caramel
 */
abstract class Plugin extends Service
{

    /**
     * @return int
     */
    abstract public function position();


    /**
     * @return string
     */
    public function getName()
    {
        return str_replace('Caramel\Plugin', "", get_class($this));
    }


    /**
     * this is called before we even touch a node
     * so we can add stuff to our config etc
     *
     * @var Dom $dom
     * @return Dom $dom
     */
    public function preProcess(Dom $dom)
    {
        return $dom;
    }


    /**
     * the function we should use for processing a node
     *
     * @var Node $node
     * @return Node $node
     */
    public function process(Node $node)
    {
        return $node;
    }


    /**
     * the function to check if we want to
     * modify a node
     *
     * @param $node
     * @return bool
     */
    public function check(Node $node)
    {
        return false;
    }


    /**
     * processes the actual node
     * if all requirements are met
     *
     * @var Node $node
     * @return Node $node
     */
    public function realProcess(Node $node)
    {
        if ($this->check($node) !== false) {
            return $this->process($node);
        } else {
            return $node;
        }
    }


    /**
     * this is called after the plugins processed
     * all nodes
     *
     * @var Dom $dom
     * @return Dom $dom
     */
    public function postProcess(Dom $dom)
    {
        return $dom;
    }


    /**
     * this is called after the plugins processed
     * all nodes and converted it into a html string
     *
     * @var string $output
     * @return string $output
     */
    public function processOutput($output)
    {
        return $output;
    }
}