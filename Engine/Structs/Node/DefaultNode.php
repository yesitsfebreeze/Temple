<?php

namespace Underware\Engine\Structs\Node;


use Underware\Engine\Exception\Exception;


/**
 * Class DefaultNode
 *
 * @package Underware\Engine\Structs
 */
class DefaultNode extends Node
{

    /** @inheritdoc */
    public function check()
    {
        return true;
    }


    /**
     * converts the line into a node
     *
     * @return Node|bool
     */
    public function setup()
    {
        return $this;
    }


    /**
     * @throws Exception
     */
    public function compile()
    {
        throw new Exception(400, "%" . $this->getTag() . "% node is not defined!");
    }


}