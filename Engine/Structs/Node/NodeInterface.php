<?php


namespace WorkingTitle\Engine\Structs\Node;


use WorkingTitle\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package WorkingTitle\Nodes
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