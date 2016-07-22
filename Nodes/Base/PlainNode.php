<?php

namespace Underware\Nodes\Base;


use Underware\Engine\Structs\Node;


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
        $output = preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain));

        return $output;
    }


}