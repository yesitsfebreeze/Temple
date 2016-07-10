<?php

namespace Underware\Plugins\Node;


use Underware\Models\Nodes\HtmlNodeModel;
use Underware\Models\Plugins\NodePlugin;


/**
 * Class Comment
 *
 * @package Underware\Plugins
 */
class Comment extends NodePlugin
{

    /** @var  string $symbol */
    private $symbol;


    /**
     * @param HtmlNodeModel $args
     *
     * @return bool
     */
    public function check($args)
    {
        if ($args instanceof HtmlNodeModel) {
            $tag = $args->get("tag.definition");
            if (!isset($tag[0])) {
                return false;
            }

            return ($tag[0] == $this->symbol && !isset($tag[1]));
        }

        return false;
    }


    /**
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel
     */
    public function process($node)
    {

        $this->symbol = $this->Instance->Config()->get("template.symbols.comment");

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
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel
     */
    private function createComment(HtmlNodeModel $node)
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
