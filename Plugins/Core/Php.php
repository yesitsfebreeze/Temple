<?php

namespace Temple\Plugin\Core;


use Temple\BaseClasses\PluginBaseClass;
use Temple\Models\NodeModel;



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
 * @author   : Stefan Hövelmanns - hvlmnns.de
 * @License : MIT
 * @package Temple
 */
class Php extends PluginBaseClass
{

    /**
     * @return int;
     */
    public function position()
    {
        return 9999999992;
    }

    /** @inheritdoc */
    public function forTags()
    {

    }

    /** @inheritdoc */
    public function forNodes()
    {

    }


    /**
     * @param NodeModel $node
     * @return bool
     */
    public function check(NodeModel $node)
    {
        return ($node->get("tag.tag") == "php");
    }


    /**
     * @param NodeModel $node
     * @return NodeModel
     * @throws \Exception
     */
    public function process(NodeModel $node)
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
     * @param NodeModel $node
     * @return NodeModel
     */
    private function processChildren(NodeModel $node)
    {
        $children = $node->get("children");
        /** @var NodeModel $child */
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