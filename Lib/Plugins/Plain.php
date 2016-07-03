<?php

namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugin;


/**
 * Class Plain
 *
 * @package Underware\Plugins
 */
class Plain extends Plugin
{

    /** @var string $identifier */
    private $identifier = "-";


    /**
     * check if we have a plain tag
     *
     * @param HtmlNode $args
     *
     * @return bool
     */
    public function check($args)
    {

        if ($args instanceof HtmlNode) {
            $tag = $args->get("tag.definition");

            return ($tag[0] == $this->identifier);
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

        return $node;
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    private function createPlain(HtmlNode $node)
    {

        if ($node->get("tag.definition") == $this->identifier) {
            $node->set("tag.opening.definition", "");
        }

        $node->set("tag.closing.definition", "");

        $node->set("tag.opening.before", "");
        $node->set("tag.opening.after", "");

        $node->set("tag.closing.before", "");
        $node->set("tag.closing.after", "\r\n");


        if (sizeof($node->get("children")) < 1 && sizeof($node->get("attributes")) < 1) {
            $node->set("tag.opening.before", "</br>");
        } else if (sizeof($node->get("children")) > 0) {
            foreach ($node->get("children") as &$childNode) {
                $childNode = $this->createPlain($childNode);
            }
        }

        return $node;
    }

}
