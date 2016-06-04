<?php

namespace Temple\Models\Plugins;


use Temple\Instance;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;


/**
 * Class Plugins
 *
 * @package Temple
 */
class Plugin implements PluginInterface
{

    /** @var Instance $Temple */
    protected $Temple;


    /**
     * @return integer
     */
    public function position()
    {
        return null;
    }


    /**
     * @return bool
     */
    public function isFunction()
    {
        return false;
    }


    /**
     * @return bool
     */
    public function forTags()
    {
        return array();
    }


    /**
     * Plugin constructor.
     *
     * @param Instance $Temple
     */
    public function __construct(Instance $Temple)
    {
        $this->Temple = $Temple;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return strrev(explode("\\", strrev(get_class($this))));
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
     * @var BaseNode $node
     * @return BaseNode $node
     */
    public function process(BaseNode $node)
    {
        return $node;
    }


    /**
     * the function to check if we want to
     * modify a node
     *
     * @param BaseNode $node
     * @return bool
     */
    public function check(BaseNode $node)
    {
        return false;
    }


    /**
     * processes the actual node
     * if all requirements are met
     *
     * @var BaseNode $node
     * @return BaseNode $node
     */
    public function realProcess(BaseNode $node)
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