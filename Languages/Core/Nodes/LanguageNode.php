<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Node\Node;


class LanguageNode extends Node
{


    /** @inheritdoc */
    public function check()
    {


        if ((strtolower($this->getTag()) == "language")) {
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