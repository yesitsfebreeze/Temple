<?php

namespace Caramel;


use Caramel\Models\Node;


/**
 * Class PluginPhp
 *
 * @purpose : converts content of an within the plain tag into plain text
 * @usage   :
 *          php
 *          die("works);
 *          result:
 *          <?php
 *          die("works");
 *          ?>
 * @autor   : Stefan Hövelmanns - hvlmnns.de
 * @License : MIT
 * @package Caramel
 */
class PluginPhp extends Models\Plugin
{

    /** @var int $position */
    protected $position = 9999999992;


    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag.tag") == "php");
    }


    /**
     * @param Node $node
     * @return Node
     * @throws \Exception
     */
    public function process($node)
    {
        $node->set("tag.opening.prefix", "<?php");
        $node->set("tag.opening.tag", "");
        $node->set("tag.opening.postfix", "");
        $node->set("tag.closing.prefix", "");
        $node->set("tag.closing.tag", "");
        $node->set("tag.closing.postfix", "?>");
        $node->set("content", " " . $node->get("attributes"));
        $node->set("attributes", "");
        if ($node->has("children")) {
            $node = $this->processChildren($node);
        }

        return $node;
    }


    /**
     * @param Node $node
     * @return Node
     */
    private function processChildren($node)
    {
        $children = $node->get("children");
        /** @var Node $child */
        foreach ($children as $child) {
            $child->set("tag.display", false);
            $child->set("plugins", false);
            $child->set("content", $child->get("tag.tag") . " " . $child->get("attributes"));
            if ($child->has("children")) {
                $this->processChildren($child);
            }
        }

        return $node;

    }

}