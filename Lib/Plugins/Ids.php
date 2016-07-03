<?php

namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugin;


/**
 * Class Ids
 *
 * @package Underware\Plugins
 */
class Ids extends Plugin
{


    /**
     * check if we have to create classes
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function check($args)
    {
        if ($args instanceof HtmlNode) {
            $tag = $args->get("tag.definition");
            preg_match("/#[^#]+/", $tag, $matches);

            return sizeof($matches) > 1;
        }

        return false;
    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode
     * @throws \Exception
     */
    public function process($node)
    {
        $tag = $node->get("tag.definition");
        preg_match("/#[^#]+/", $tag, $matches);

        $ids  = $this->getIds($tag, explode("#", $tag));
        $node = $this->updateTag($node, $tag, $ids);
        $node = $this->setAttribute($node, $ids);

        return $node;
    }


    /**
     * @param string $tag
     * @param array  $ids
     *
     * @return string
     */
    private function getIds($tag, $ids)
    {
        # remove real tag if first item
        # when it's not a class
        # or if its a id
        if ($tag[0] != "#" || $tag[0] == "." || $ids[0] == "") array_shift($ids);
        $concat = '';
        foreach ($ids as &$id) {
            $class = strpos($id, ".");
            if ($class) $id = substr($id, 0, $class);
            $concat .= " " . $id;
        }
        $ids = substr($concat, 1);

        return $ids;
    }


    /**
     * @param HtmlNode $node
     * @param string   $tag
     * @param array    $ids
     *
     * @return mixed
     */
    private function updateTag(HtmlNode $node, $tag, $ids)
    {
        foreach (explode(" ", $ids) as $id) {
            $tag = str_replace('#' . $id, "", $tag);
        }

        if (trim($tag) == '') {
            $tag = $this->createNewTag($node);
        }

        $node->set("tag.definition", $tag);
        $node->set("tag.opening.definition", $tag);
        $node->set("tag.closing.definition", $tag);

        return $node;
    }


    /**
     * @param HtmlNode $node
     * @param array    $ids
     *
     * @return HtmlNode
     * @throws \Exception
     */
    private function setAttribute(HtmlNode $node, $ids)
    {
        /** @var HtmlNode $node */
        $attributes = $node->get("attributes");
        if (sizeof($attributes) == 0) {
            $attributes["id"] = $ids;
        } else {
            $attributes["id"] = $attributes["id"] . " " . $ids;
        }

        $node->set("attributes", $attributes);

        return $node;
    }


    /**
     * @param HtmlNode $node
     *
     * @return string
     * @throws \Exception
     */
    private function createNewTag(HtmlNode $node)
    {
        # check if tag is empty now
        # if so create div or span,
        # depending if we have a brick or inline parent element
        $inline = false;
        if ($node->has("parent")) {
            $parentTag = $node->get("parent")->get("tag.definition");
            $inline    = in_array($parentTag, $this->Instance->Config()->get("parser.inline"));
        }
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}