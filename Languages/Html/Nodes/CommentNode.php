<?php

namespace Rite\Languages\Html\Nodes;


use Rite\Engine\Structs\Node\Node;


class CommentNode extends Node
{

    public function check()
    {
        if (substr(trim($this->plain), 0, 1) == "#") {
            return true;
        }

        return false;
    }


    /** @inheritdoc */
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
        if ($this->Instance->Config()->isShowComments()) {
            $output = "<!-- ";
            $output .= preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain));
            $output .= " -->";
        } else {
            $output = "";
        }

        return $output;
    }


}