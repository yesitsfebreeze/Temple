<?php

namespace Underware\Languages\Html\Nodes;


use Underware\Engine\Structs\Node\Node;


class PlainNode extends Node
{

    /** @inheritdoc */
    public function check()
    {
        if ($this->getTag() == "-") {
            return true;
        }

        return false;
    }


    /**
     * converts the line into a node
     *
     * @return Node|bool
     */
    public function setup()
    {
        $this->setSelfClosing(true);

        return $this;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = trim(preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain)));

        return $output;
    }


}