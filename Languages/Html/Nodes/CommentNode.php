<?php

namespace Temple\Languages\Html\Nodes;


use Temple\Engine\Structs\Node\Node;


/**
 * Class CommentNode
 *
 * @package Temple\Languages\Html\Nodes
 */
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
        $this->setCommentNode(true);

        $lastNode = $this->getLastRealNode($this);
        if ($lastNode) {
            $this->setIndent($lastNode->getIndent());
        }
        
        return $this;
    }


    /**
     * returns the closest node which is not a comment
     *
     * @param Node $node
     *
     * @return Node|bool
     */
    private function getLastRealNode(Node $node)
    {
        $lastNode = $node->getPreviousNode();
        if (!is_null($lastNode)) {
            if (!$lastNode->isCommentNode()) {
                return $lastNode;
            } else if ($lastNode instanceof Node) {
                return $this->getLastRealNode($lastNode);
            }
        }

        return false;
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
            $output .= trim(preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain)));
            $output .= " -->";
        } else {
            $output = "";
        }

        return $output;
    }

}