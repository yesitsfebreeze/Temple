<?php

namespace Temple\Plugin\Html;


use Temple\BaseClasses\PluginBaseClass;
use Temple\Models\NodeModel;


/**
 * Class PluginPlain
 *
 * @package     Temple
 * @description converts a node to plain text
 * @position    3
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Plain extends PluginBaseClass
{

    /** @var string $symbol */
    private $symbol = "-";


    /**
     * @return int;
     */
    public function position()
    {
        return 1;
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
        $tag = $node->get("tag.tag");

        return ($tag[0] == $this->symbol);
    }


    /**
     * @param NodeModel $node
     * @return NodeModel
     * @throws \Exception
     */
    public function process(NodeModel $node)
    {

        # create the plain node
        $node = $this->createPlain($node);

        if ($node->has("children")) {
            # if we have children add a linebreak to the comment
            # for better readability
            $node->set("tag.opening.prefix", "");
            $node->set("tag.closing.postfix", "");
            # recursively process all children
            $this->processChildren($node);

        }

        return $node;
    }


    /**
     * recursively iterates over our children and
     * adjust them for the comment
     *
     * @param NodeModel $node
     */
    private function processChildren(NodeModel $node)
    {
        $children = $node->get("children");
        /** @var NodeModel $child */
        foreach ($children as $child) {
            $child = $this->createPlain($child, true);
            if ($child->get("tag.tag") == $this->symbol && $child->get("attributes") == "") {
                $child->set("content", "</br>");
            }

            if ($child->has("children")) {
                $this->processChildren($child);
            }
        }
    }


    /**
     * creates the comment from the current node
     *
     * @param NodeModel $node
     * @param boolean   $child
     * @return mixed
     */
    private function createPlain(NodeModel $node, $child = false)
    {
        $node->set("tag.opening.display", false);
        $node->set("tag.closing.display", false);
        $node->set("plugins", false);
        $node->set("content", $node->get("attributes"));
        $node->set("content", $node->get("content"));
        if ($child) {
            $node->set("content", " " .$node->get("tag.opening.tag") . " " . $node->get("content"));
        }

        # replace all php tags for security reasons
        $content = str_replace("<?php", "", $node->get("content"));
        $content = str_replace("?>", "", $content);
        $node->set("content", $content);

        return $node;
    }


}