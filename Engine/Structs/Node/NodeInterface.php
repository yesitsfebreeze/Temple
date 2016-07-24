<?php


namespace Underware\Engine\Structs\Node;


use Underware\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package Underware\Nodes
 */
interface NodeInterface
{

    /**
     * @return mixed
     */
    public function setup();


    /**
     * @return mixed
     */
    public function compile();


    /**
     * @param Dom $Dom
     *
     * @return Dom
     */
    public function setDom(Dom $Dom);


    /**
     * @return Dom
     */
    public function getDom();

}