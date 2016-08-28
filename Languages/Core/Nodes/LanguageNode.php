<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Node\Node;


/**
 * this node us just for disabling the rendering of the language tag
 * Class LanguageNode
 *
 * @package Temple\Languages\Core\Nodes
 */
class LanguageNode extends Node
{


    /** @inheritdoc */
    public function check()
    {

        if ((strtolower($this->getTag()) == $this->Instance->Config()->getLanguageTagName())) {
            return true;
        }

        return false;
    }


    /**
     * converts the line into a node
     *
     * @return $this
     * @throws Exception
     */
    public function setup()
    {
        return $this;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        return "";
    }


}