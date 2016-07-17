<?php


namespace Underware\Engine\Structs;


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