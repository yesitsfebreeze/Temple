<?php

namespace Pavel\Plugins;


use Pavel\Models\HtmlNode;
use Pavel\Models\Plugin;


/**
 * Class PluginComment
 *
 * @purpose  converts line to comment with all of its children
 * @usage    # at linestart
 * @author   Stefan Hövelmanns - hvlmnns.de
 * @License  MIT
 * @package  Pavel
 */
class Comment extends Plugin
{

    /** @var  string $symbol */
    private $symbol;


    /**
     * @param HtmlNode $node
     *
     * @return bool
     */
    public function check(HtmlNode $node)
    {
        $tag = $node->get("tag.definition");
        if (!isset($tag[0])) {
            return false;
        }

        return ($tag[0] == $this->symbol && !isset($tag[1]));
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    public function process($node)
    {

        $this->symbol = $this->Instance->Config()->get("template.symbols.comment");
        if (!$this->check($node)) {
            return $node;
        }

        if ($this->Instance->Config()->get("template.comments.show")) {
            $node = $this->createComment($node);

            if (sizeof($node->get("children")) > 0) {
                $node->set("tag.opening.before", "<!--\n");
                $node->set("tag.closing.after", "--!>");
                $node->set("tag.opening.after", "\n");
                $node->set("info.attributes.trim", true);
            } else {
                $node->set("tag.opening.before", "<!-- ");
                $node->set("tag.closing.after", " --!>");
            }
        } else {
            $node->set("tag.display", false);
        }

        return $node;
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    private function createComment(HtmlNode $node)
    {

        if ($node->get("tag.definition") == $this->symbol) {
            $node->set("tag.opening.definition", "");
        }

        $node->set("tag.closing.definition", "");

        $node->set("tag.opening.before", "");
        $node->set("tag.opening.after", "");

        $node->set("tag.closing.before", "");
        $node->set("tag.closing.after", "\n");

        if (sizeof($node->get("children")) > 0) {
            foreach ($node->get("children") as &$childNode) {
                $childNode = $this->createComment($childNode);
            }
        }

        return $node;
    }

}
