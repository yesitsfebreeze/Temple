<?php

namespace Shift\Plugin;


use Shift\Models\HtmlNode;
use Shift\Models\Plugin;


/**
 * Class PluginComment
 *
 * @purpose  converts line to plain text
 * @usage    - at linestart
 * @author   Stefan Hövelmanns - hvlmnns.de
 * @License  MIT
 * @package  Shift
 */
class Plain extends Plugin
{

    /** @var string $identifier */
    private $identifier = "-";


    /**
     * @return int;
     */
    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isProcessor()
    {
        return true;
    }


    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    public function process(HtmlNode $node)
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

        if (sizeof($node->get("children")) == 0 && sizeof($node->get("attributes")) == 0) {
            $node->set("tag.opening.before", "</br>");
        } else if (sizeof($node->get("children")) > 0) {
            foreach ($node->get("children") as &$childNode) {
                $childNode = $this->createPlain($childNode);
            }
        }

        return $node;
    }

}
