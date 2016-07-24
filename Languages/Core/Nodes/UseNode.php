<?php

namespace Underware\Languages\Core\Nodes;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Structs\Node\Node;


class UseNode extends Node
{


    /** @inheritdoc */
    public function check()
    {


        if ((strtolower($this->getTag()) == "use")) {
            return true;
        }

        $this->setup();

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
     * returns the language class
     *
     * @return string
     */
    public function getLanguage()
    {
        return "";
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        return false;
    }


}