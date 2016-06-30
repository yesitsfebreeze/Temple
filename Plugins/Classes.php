<?php

namespace Shift\Plugin;


use Shift\Models\HtmlNode;
use Shift\Models\Plugin;


/**
 * Classes Plugin
 *
 * @description  converts emmet inspired class definition to actual classes
 * @usage        div.myclass.myotherclass results in <div class="myclass myotherclass"></div>
 * @author       Stefan Hövelmanns - hvlmnns.de
 * @License      MIT
 * @package      Shift
 */
class Classes extends Plugin
{


    /**
     * @return int;
     */
    public function position()
    {
        return 3;
    }


    public function isProcessor()
    {
        return true;
    }


    /**
     * @param HtmlNode $node
     * @return HtmlNode
     * @throws \Exception
     */
    public function process(HtmlNode $node)
    {
        $tag     = $node->get("tag.definition");
        $classes = explode(".", $tag);
        if (sizeof($classes) > 1) {
            $classes = $this->getClasses($tag, $classes);
            $node    = $this->updateTag($node, $tag, $classes);
            $node    = $this->setAttribute($node, $classes);
        }

        return $node;
    }


    /**
     * @param string $tag
     * @param array  $classes
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
            $inline    = in_array($parentTag, $this->Shift->Config()->get("parser.inline"));
        }
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}