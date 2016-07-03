<?php

namespace Pavel\Plugins;


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
class Php extends Plugin
{

    /** @var string $identifier */
    private $identifier = "php";

    /**
     * @param HtmlNode $node
     * @return HtmlNode
     */
    public function process($node)
    {

        $tag = $node->get("tag.definition");

        if ($tag == $this->identifier) {
            $node = $this->createPlain($node);
            $node->set("info.isPlain", false);
            $node->set("content", "");
            $node->set("tag.opening.before", "<?php ");
            $node->set("tag.opening.definition", "");
            $node->set("tag.opening.after", "");
            $node->set("tag.closing.before", "");
            $node->set("tag.closing.definition", "");
            $node->set("tag.closing.after", " ?>");
        }

        return $node;
    }


    /**
     * @param HtmlNode $node
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
