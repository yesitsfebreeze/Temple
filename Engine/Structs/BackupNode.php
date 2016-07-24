<?php

namespace Underware\Engine\Structs;


/**
 * Class BackupNode
 *
 * @package Underware\Engine\Structs
 */
class BackupNode extends Node
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
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = "";
        if (trim($this->plain) != "") {
            $output .= "%%" . trim($this->plain) . "%% ";
        }
        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $output .= $child->compile();
        }

        return $output;
    }


}