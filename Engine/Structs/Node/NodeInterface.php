<?php


namespace Temple\Engine\Structs\Node;


use Temple\Engine\Structs\Dom;


/**
 * Interface Node
 *
 * @package Temple\Nodes
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