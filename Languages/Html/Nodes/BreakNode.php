<?php

namespace Temple\Languages\Html\Nodes;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Node\Node;


/**
 * Class BreakNode
 *
 * @package Temple\Languages\Html\Nodes
 */
class BreakNode extends Node
{

    /** @inheritdoc */
    public function check()
    {
        if (strtolower($this->getTag()) == "break") {
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
        return $this;
    }

    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = '<?php break; ?>';

        return $output;
    }


}