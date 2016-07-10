<?php

namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugins\NodePlugin;


/**
 * Class Php
 *
 * @package Underware\Plugins
 */
class Php extends NodePlugin
{
    

    /**
     * check if we have a php tag
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function check($args)
    {
        if ($args instanceof HtmlNode) {
            $tag = $args->get("tag.definition");

            return ($tag == "php");
        }

        return false;
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    public function process($node)
    {

        $node = $this->createPlain($node);
        $node->set("info.isPlain", false);
        $node->set("content", "");
        $node->set("tag.opening.before", "<?php ");
        $node->set("tag.opening.definition", "");
        $node->set("tag.opening.after", "");
        $node->set("tag.closing.before", "");
        $node->set("tag.closing.definition", "");
        $node->set("tag.closing.after", " ?>");

        return $node;
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    private function createPlain(HtmlNode $node)
    {

        $node->set("info.isPlain", true);


        if (sizeof($node->get("children")) > 0) {
            $children = array();
            foreach ($node->get("children") as $key => $childNode) {
                $children[ $key ] = $this->createPlain($childNode);
            }

            $node->set("children", $children);
        }

        return $node;
    }

}
