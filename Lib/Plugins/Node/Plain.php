<?php

namespace Underware\Plugins\Node;


use Underware\Models\Nodes\HtmlNodeModel;
use Underware\Models\Plugins\NodePlugin;


/**
 * Class Plain
 *
 * @package Underware\Plugins
 */
class Plain extends NodePlugin
{

    /** @var string $identifier */
    private $identifier = "-";


    /**
     * check if we have a plain tag
     *
     * @param HtmlNodeModel $args
     *
     * @return bool
     */
    public function check($args)
    {

        if ($args instanceof HtmlNodeModel) {
            $tag = $args->get("tag.definition");

            return ($tag[0] == $this->identifier);
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
        $node = $this->createPlain($node);

        return $node;
    }


    /**
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel
     */
    private function createPlain(HtmlNodeModel $node)
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
