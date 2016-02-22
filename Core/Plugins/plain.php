<?php

namespace Caramel;


/**
 *
 * Class PluginPlain
 *
 * @purpose: converts content of an within the plain tag into plain text
 * @usage:
 *
 *      - my text
 *          another text
 *
 *      - or you can
 *      - write like this
 *
 *      - a line with just a - inside a plain tag will create a break
 *        -
 *        like this
 *
 *      -- this text has no trailing space,
 *         which is otherwise added by default
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginPlain extends Plugin
{

    /** @var int $position */
    protected $position = 25;

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

            $trailing = true;
            # if we have a double minus we don't use the trailing space
            if ($node->get("tag/tag") == "--") $trailing = false;

            # create the plain node
            $node = $this->createPlain($node, $trailing);

            if ($node->has("children")) {
                # if we have children add a linebreak to the comment
                # for better readability
                $node->set("tag/opening/prefix", "");
                $node->set("tag/closing/postfix", "");
                # recursively process all children
                $this->processChildren($node, $trailing);
            }

        }

        return $node;
    }


    /**
     * recursively iterates over our children and
     * adjust them for the comment
     *
     * @param Node $node
     * @param boolean $trailing
     */
    private function processChildren($node, $trailing)
    {
        $children = $node->get("children");
        /** @var Node $child */
        foreach ($children as $child) {
            $child = $this->createPlain($child, $trailing, true);
            if ($child->get("tag/tag") == "-" && $child->get("attributes") == "") {
                $child->set("content", "</br>");
            }

            if ($child->has("children")) {
                $this->processChildren($child, $trailing);
            }
        }
    }


    /**
     * creates the comment from the current node
     *
     * @param Node $node
     * @param boolean $trailing
     * @param boolean $child
     * @return mixed
     */
    private function createPlain($node, $trailing, $child = false)
    {
        $node->set("tag/opening/display", false);
        $node->set("tag/closing/display", false);
        $node->set("plugins", false);
        $node->set("content", $node->get("attributes"));
        if ($trailing) {
            $node->set("content", $node->get("content") . " ");
        }
        if ($child) {
            $node->set("content", $node->get("tag/opening/tag") . " " . $node->get("content"));
        }

        return $node;
    }

    /**
     * checks if we are within a plain declaration
     *
     * @param Node $node
     * @return bool
     */
    private function checkTag($node)
    {
        return ($node->get("tag/tag")[0] == "-");
    }


}