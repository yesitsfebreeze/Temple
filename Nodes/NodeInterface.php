<?php


namespace Underware\Nodes;


use Underware\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package Underware\Nodes
 */
interface NodeInterface
{

    /**
     * @param $line
     *
     * @return mixed
     */
    public function create($line);


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