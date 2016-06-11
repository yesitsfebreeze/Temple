<?php

namespace Temple\Plugin;


use Temple\Models\Nodes\HtmlNode;
use Temple\Models\Plugin\Plugin;


/**
 * Class PluginComment
 *
 * @purpose  converts line to plain text
 * @usage    - at linestart
 * @author   Stefan Hövelmanns - hvlmnns.de
 * @License  MIT
 * @package  Temple
 */
class Plain extends Plugin
{

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
     * @return bool
     */
    public function check(HtmlNode $node)
    {
        $tag = $node->get("tag.definition");

        return ($tag[0] == "-");
    }


    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    public function process(HtmlNode $node)
    {
        if (!$this->check($node)) {
            return $node;
        }

        $node = $this->createComment($node);

        return $node;
    }


    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    private function createComment(HtmlNode $node)
    {

        if ($node->get("tag.definition") == "-") {
            $node->set("tag.opening.definition", "");
        }

        $node->set("tag.closing.definition", "");

        $node->set("tag.opening.before", "");
        $node->set("tag.opening.after", "");

        $node->set("tag.closing.before", "");
        $node->set("tag.closing.after", "\r\n");

        if (sizeof($node->get("children")) == 0 && sizeof($node->get("attributes")) == 0 ) {
            $node->set("tag.opening.before", "</br>");
        } else if (sizeof($node->get("children")) > 0) {
            foreach ($node->get("children") as &$childNode) {
                $childNode = $this->createComment($childNode);
            }
        }

        return $node;
    }

}
