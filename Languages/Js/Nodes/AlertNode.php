<?php

namespace Temple\Languages\Js\Nodes;


use Temple\Engine\Structs\Node\Node;


/**
 * Class BlockNode
 *
 * @package Temple\Languages\Core\Nodes
 */
class AlertNode extends Node
{


    public function check()
    {
        if ($this->getTag() == "test") {
            return true;
        }

        return false;
    }

    public function setup()
    {

    }

    public function compile()
    {
        return "alert('alert');";
    }
}
