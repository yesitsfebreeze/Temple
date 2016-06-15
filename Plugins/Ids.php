<?php

namespace Temple\Plugin;


use Temple\Models\Nodes\HtmlNode;
use Temple\Models\Plugin\Plugin;


/**
 * Class PluginIds
 *
 * @purpose  : converts emmet inspired id definition to actual ids
 * @usage    : div#myid#myotherid id="default"
 * @author   : Stefan Hövelmanns - hvlmnns.de
 * @License  : MIT
 * @package  Temple
 */
class Ids extends Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 4;
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
        $ids = explode("#", $tag);
        if (sizeof($ids) > 1) {
            $ids  = $this->getIds($tag, $ids);
            $node = $this->updateTag($node, $tag, $ids);
            $node = $this->setAttribute($node, $ids);
        }

        return $node;
    }


    /**
     * @param string $tag
     * @param array  $ids
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
     * @param string    $tag
     * @param array     $ids
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
     * @param array     $ids
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
            $inline    = in_array($parentTag, $this->Temple->Config()->get("parser.inline"));
        }
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}