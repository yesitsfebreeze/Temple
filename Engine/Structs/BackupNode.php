<?php

namespace Underware\Engine\Structs;


class BackupNode extends Node
{


    /** @var  string $blockName */
    private $blockName;


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
        $output = "<pre style='margin:0'>" . $this->plain . "</pre>";
        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $output .= $child->compile();
        }

        return $output;
    }


}