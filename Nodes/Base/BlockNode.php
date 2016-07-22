<?php

namespace Underware\Nodes\Base;


use Underware\Engine\Structs\Node;


class BlockNode extends Node
{


    /** @var  string $blockName */
    private $blockName;


    /** @inheritdoc */
    public function check()
    {
        if ($this->getTag() == "block") {
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
        $this->setBlockName("test");
        $this->Dom->addBlock($this);
        return $this;
    }


    /**
     * @return string
     */
    public function getBlockName()
    {
        return $this->blockName;
    }


    /**
     * @param string $blockName
     */
    public function setBlockName($blockName)
    {
        $this->blockName = $blockName;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = "";
        if ($this->Instance->Config()->isShowBlockComments()) {
            $output = "<!-- " . trim($this->plain) . " - " . $this->getRelativeFile() . "-->";
        }

        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $output .= $child->compile();
        }

        return $output;
    }


}