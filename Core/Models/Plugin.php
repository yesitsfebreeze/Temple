<?php

namespace Caramel\Models;


use Caramel\Caramel;


/**
 * Class Plugin
 *
 * @package Caramel
 */
abstract class Plugin
{
    /** @var  integer $position */
    protected $position;


    /**
     * Plugin constructor.
     *
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->caramel = $caramel;
    }


    /**
     * @return int
     * @throws \Exception
     */
    public function getPosition()
    {
        if (!is_null($this->position)) {
            return $this->position;
        } else {
            $pluginName = str_replace("\\", "&#92;", get_class($this));
            throw new \Exception("you need to set a position for " . $pluginName . "!");
        }
    }


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
    public function preProcess($dom)
    {
        return $dom;
    }


    /**
     * the function we should use for processing a node
     *
     * @var Node $node
     * @return Node $node
     */
    public function process($node)
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
    public function check($node)
    {
        return $node;
    }


    /**
     * processes the actual node
     * if all requirements are met
     *
     * @var Node $node
     * @return Node $node
     */
    public function realProcess($node)
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
    public function postProcess($dom)
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