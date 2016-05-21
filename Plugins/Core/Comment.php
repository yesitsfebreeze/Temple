<?php

namespace Caramel\Plugins\Core;


use Caramel\Models\NodeModel;
use Caramel\Models\PluginModel;

/**
 * Class PluginComment
 *
 * @purpose : converts line to comment with all of its children
 * @usage   : # at linestart
 * @author   : Stefan Hövelmanns - hvlmnns.de
 * @License : MIT
 * @package Caramel
 */
class Comment extends PluginModel
{

    /**
     * @return int;
     */
    public function position()
    {
        return 2;
    }


    /**
     * @param NodeModel $node
     * @return bool
     */
    public function check(NodeModel $node)
    {
        $tag = $node->get("tag.tag");

        return ($tag[0] == $this->config->get("comment_symbol") && ($tag[1] == " " || $tag[1] == ""));
    }


    /**
     * @param NodeModel $node
     * @return NodeModel
     * @throws \Exception
     */
    public function process(NodeModel $node)
    {

        # create the comment
        $node = $this->createComment($node);

        if ($node->has("children")) {
            # if we have children add a linebreak to the comment
            # for better readability
            $node->set("tag.opening.prefix", "<!--\n\r");
            $node->set("tag.closing.postfix", "\n\r--!>");
            # recursively process all children
            $this->processChildren($node);
        }

        return $node;
    }


    /**
     * creates the comment from the current node

     *
*@param NodeModel $node
     * @return mixed
     */
    private function createComment(NodeModel $node)
    {
        $node->set("tag.opening.prefix", "<!-- ");
        $node->set("tag.opening.tag", "");
        $node->set("tag.opening.postfix", "");
        $node->set("tag.closing.prefix", "");
        $node->set("tag.closing.tag", "");
        $node->set("tag.closing.postfix", " --!>");
        $node->set("plugins", false);

        return $node;
    }


    /**
     * recursively iterates over our children and
     * adjust them for the comment

     *
*@param NodeModel $node
     */
    private function processChildren(NodeModel $node)
    {
        $children = $node->get("children");
        foreach ($children as $child) {
            $child = $this->createCommentChild($child);
            if ($child->has("children")) {
                $this->processChildren($child);
            }
        }
    }


    /**
     * removes the pre/postfixes and the closing tag

     *
*@param NodeModel $node
     * @return mixed
     */
    private function createCommentChild(NodeModel $node)
    {
        $node->set("tag.opening.prefix", "\r\n");
        $node->set("tag.opening.postfix", "");
        $node->set("tag.closing.prefix", "");
        $node->set("tag.closing.tag", "");
        $node->set("tag.closing.postfix", "");
        $node->set("plugins", false);
        $node = $this->addIndent($node);

        return $node;
    }


    /**
     * adds the template indent to the comment

*
*@param NodeModel $node
     * @return NodeModel
     */
    private function addIndent(NodeModel $node)
    {
        $replaceItem = "tag.opening.prefix";
        $indent      = str_repeat($node->get("dom")->get("template.indent.char"), $node->get("dom")->get("template.indent.amount"));
        $indent      = str_repeat($indent, $node->get("indent"));
        $node->set($replaceItem, $node->get($replaceItem) . $indent);

        return $node;
    }

}
