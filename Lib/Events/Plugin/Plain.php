<?php

namespace Pavel\Events\Plugin;


use Pavel\Models\HtmlNode;
use Pavel\Models\Plugin;


/**
 * Class PluginComment
 *
 * @purpose  converts line to plain text
 * @usage    - at linestart
 * @author   Stefan Hövelmanns - hvlmnns.de
 * @License  MIT
 * @package  Pavel
 */
class Plain extends Plugin
{

    /** @var string $identifier */
    private $identifier = "-";

    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    public function process($node)
    {
        $tag = $node->get("tag.definition");

        if ($tag[0] == $this->identifier) {
            $node = $this->createPlain($node);
        }

        return $node;
    }


    /**
     * @param HtmlNode $node
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
