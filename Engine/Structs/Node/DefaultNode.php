<?php

namespace Temple\Engine\Structs\Node;


use Temple\Engine\Exception\Exception;


/**
 * Class DefaultNode
 *
 * @package Temple\Engine\Structs
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
        $language = $this->getDom()->getLanguage()->getName();
        throw new Exception(400, "The %" . $this->getTag() . "% node for the language %" . $language . "% is not defined!", $this->getDom()->getFile(), $this->getLine());
    }


}