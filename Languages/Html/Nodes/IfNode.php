<?php

namespace Temple\Languages\Html\Nodes;


use Temple\Engine\Structs\Node\Node;


/**
 * Class BreakNode
 *
 * @package Temple\Languages\Html\Nodes
 */
class IfNode extends Node
{

    /** @inheritdoc */
    public function check()
    {
        if (strtolower($this->getTag()) == "if") {
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
        $this->setFunction(true);
        return $this;
    }

    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {


        $output = "<?php if ( ".$this->getContent()." ) { ?>";

        $output .= $this->compileChildren();

        $output .= "<?php } ?>";

        return $output;
    }


}