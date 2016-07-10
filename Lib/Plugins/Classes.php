<?php

namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugins\NodePlugin;


/**
 * Class Classes
 *
 * @package Underware\Plugin
 */
class Classes extends NodePlugin
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
            $tag     = $args->get("tag.definition");
            $classes = explode(".", $tag);

            return sizeof($classes) > 1;
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
        $tag     = $node->get("tag.definition");
        $classes = explode(".", $tag);

        $classes = $this->getClasses($tag, $classes);
        $node    = $this->updateTag($node, $tag, $classes);
        $node    = $this->setAttribute($node, $classes);

        return $node;
    }


    /**
     * @param string $tag
     * @param array  $classes
     *
     * @return string
     */
    private function getClasses($tag, $classes)
    {
        # remove real tag if first item
        # when it's not a class
        # or if its a id
        if ($tag[0] != "." || $tag[0] == "#" || $classes[0] == "") array_shift($classes);
        $concat = '';
        foreach ($classes as &$class) {
            $id = strpos($class, "#");
            if ($id) $class = substr($class, 0, $id);
            $concat .= " " . $class;
        }
        $classes = substr($concat, 1);

        return $classes;
    }


    /**
     * @param HtmlNode $node
     * @param string   $tag
     * @param array    $classes
     *
     * @return mixed
     */
    private function updateTag(HtmlNode $node, $tag, $classes)
    {
        foreach (explode(" ", $classes) as $class) {
            $tag = str_replace('.' . $class, "", $tag);
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
     * @param array    $classes
     *
     * @return HtmlNode
     * @throws \Exception
     */
    private function setAttribute(HtmlNode $node, $classes)
    {
        /** @var HtmlNode $node */
        $attributes = $node->get("attributes");
        if (sizeof($attributes) == 0) {
            $attributes["class"] = $classes;
        } else {
            $attributes["class"] = $attributes["class"] . " " . $classes;
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
        # depending if we have a block or inline parent element
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