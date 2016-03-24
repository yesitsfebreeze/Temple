<?php

namespace Caramel\Plugins\Core;


use Caramel\Models\Node;
use Caramel\Plugin\Plugin;


/**
 * Class PluginPlain
 *
 * @package     Caramel
 * @description converts a node to plain text
 * @position    3
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Plain extends Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 1;
    }


    /**
     * @param Node $node
     * @return bool
     */
    public function check(Node $node)
    {
        $tag = $node->get("tag.tag");
        return ($tag[0] == ">");
    }


    /**
     * @param Node $node
     * @return Node
     * @throws \Exception
     */
    public function process(Node $node)
    {

        $trailing = true;
        # if we have a double minus we don't use the trailing space
        if ($node->get("tag.tag") == ">>") $trailing = false;

        # create the plain node
        $node = $this->createPlain($node, $trailing);

        if ($node->has("children")) {
            # if we have children add a linebreak to the comment
            # for better readability
            $node->set("tag.opening.prefix", "");
            $node->set("tag.closing.postfix", "");
            # recursively process all children
            $this->processChildren($node, $trailing);
        }

        return $node;
    }


    /**
     * recursively iterates over our children and
     * adjust them for the comment
     *
     * @param Node    $node
     * @param boolean $trailing
     */
    private function processChildren(Node $node, $trailing)
    {
        $children = $node->get("children");
        /** @var Node $child */
        foreach ($children as $child) {
            $child = $this->createPlain($child, $trailing, true);
            if ($child->get("tag.tag") == ">" && $child->get("attributes") == "") {
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
     * @param Node    $node
     * @param boolean $trailing
     * @param boolean $child
     * @return mixed
     */
    private function createPlain(Node $node, $trailing, $child = false)
    {
        $node->set("tag.opening.display", false);
        $node->set("tag.closing.display", false);
        $node->set("plugins", false);
        $node->set("content", $node->get("attributes"));
        if ($trailing) {
            $node->set("content", $node->get("content") . " ");
        }
        if ($child) {
            $node->set("content", $node->get("tag.opening.tag") . " " . $node->get("content"));
        }

        # replace all php tags for security reasons
        $content = str_replace("<?php", "", $node->get("content"));
        $content = str_replace("?>", "", $content);
        $node->set("content", $content);

        return $node;
    }


}