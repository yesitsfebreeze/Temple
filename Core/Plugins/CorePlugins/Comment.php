<?php

namespace Caramel;


/**
 *
 * Class Caramel__Plugin_Comment
 *
 * @purpose: converts line to comment with all of its children
 * @usage: # at linestart
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginComment extends Plugin
{

    /** @var int $position */
    protected $position = 0;

    /**
     * @param Node $node
     * @return Storage
     * @throws \Exception
     */
    public function process($node)
    {

        # check if we have a tag
        $pass = $this->checkTag($node);

        if ($pass) {
            # create the comment
            $node = $this->createComment($node);

            if ($node->has("children")) {
                # if we have children add a linebreak to the comment
                # for better readability
                $node->set("tag/opening/prefix", "<!--\n");
                $node->set("tag/closing/postfix", "\n--!>");
                # recursively process all children
                $this->processChildren($node);
            }

        }

        return $node;
    }

    /**
     * checks if we are within a comment declaration
     *
     * @param Node $node
     * @return bool
     */
    private function checkTag($node)
    {
        return ($node->get("tag/tag")[0] == $this->caramel->config()->get("comment_symbol") && ($node->get("tag/tag")[1] == " " || $node->get("tag/tag")[1] == ""));
    }


    /**
     * creates the comment from the current node
     *
     * @param Node $node
     * @return mixed
     */
    private function createComment($node)
    {
        $node->set("tag/opening/prefix", "<!-- ");
        $node->set("tag/opening/tag", "");
        $node->set("tag/opening/postfix", "");
        $node->set("tag/closing/prefix", "");
        $node->set("tag/closing/tag", "");
        $node->set("tag/closing/postfix", " --!>");
        $node->set("plugins", false);

        return $node;
    }


    /**
     * recursively iterates over our children and
     * adjust them for the comment
     *
     * @param Node $node
     */
    private function processChildren($node)
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
     * @param Node $node
     * @return mixed
     */
    private function createCommentChild($node)
    {
        $node->set("tag/opening/prefix", "\n");
        $node->set("tag/opening/postfix", "");
        $node->set("tag/closing/prefix", "");
        $node->set("tag/closing/tag", "");
        $node->set("tag/closing/postfix", "");
        $node->set("plugins", false);

        return $node;
    }

}