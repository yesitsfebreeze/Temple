<?php

namespace Temple\Plugin;


use Temple\Models\HtmlNode;
use Temple\Models\Plugin;


/**
 * Class PluginComment
 *
 * @purpose  converts line to comment with all of its children
 * @usage    # at linestart
 * @author   Stefan Hövelmanns - hvlmnns.de
 * @License  MIT
 * @package  Temple
 */
class Comment extends Plugin
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

        return ($tag[0] == "#" && !isset($tag[1]));
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

        if (sizeof($node->get("children")) > 0) {
            $node->set("tag.opening.before", "<!--\r\n");
            $node->set("tag.closing.after", "--!>");
            $node->set("tag.opening.after", "\r\n");
        } else {
            $node->set("tag.opening.before", "<!-- ");
            $node->set("tag.closing.after", " --!>");
        }

        return $node;
    }


    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    private function createComment(HtmlNode $node)
    {
        if ($node->get("tag.definition") == "#") {
            $node->set("tag.opening.definition", "");
        }

        $node->set("tag.closing.definition", "");

        $node->set("tag.opening.before", "");
        $node->set("tag.opening.after", "");

        $node->set("tag.closing.before", "");
        $node->set("tag.closing.after", "\r\n");

        if (sizeof($node->get("children")) > 0) {
            foreach ($node->get("children") as &$childNode) {
                $childNode = $this->createComment($childNode);
            }
        }

        return $node;
    }

}
