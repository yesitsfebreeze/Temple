<?php


namespace Rite\Engine\Structs\Node;


use Rite\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package Rite\Nodes
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