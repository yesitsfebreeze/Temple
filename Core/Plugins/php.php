<?php

namespace Caramel;


/**
 *
 * Class PluginPhp
 *
 * @purpose: converts content of an within the plain tag into plain text
 * @usage:
 *
 *      php
 *         die("works);
 *
 *  result:
 *
 *      <?php
 *          die("works");
 *      ?>
 *
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginPhp extends Plugin
{

    /** @var int $position */
    protected $position = 9999999992;

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
            $node->set("tag/opening/prefix", "<?php");
            $node->set("tag/opening/tag", "");
            $node->set("tag/opening/postfix", "");
            $node->set("tag/closing/prefix", "");
            $node->set("tag/closing/tag", "");
            $node->set("tag/closing/postfix", "?>");
            $node->set("content", " " . $node->get("attributes"));
            $node->set("attributes", "");
            if ($node->has("children")) {
                $node = $this->processChildren($node);
            }
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
            $child->set("tag/display", false);
            $child->set("plugins", false);
            $child->set("content", $child->get("tag/tag") . " " . $child->get("attributes"));
            if ($child->has("children")) {
                $this->processChildren($child);
            }
        }

        return $node;

    }

    /**
     * checks if we are within a php declaration
     *
     * @param Node $node
     * @return bool
     */
    private function checkTag($node)
    {
        return ($node->get("tag/tag") == "php");
    }

}