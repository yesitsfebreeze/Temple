<?php

namespace Caramel;


use Caramel\Models\Node;


/**
 * Class PluginIds
 *
 * @purpose : converts emmet inspired id definition to actual ids
 * @usage   : div#myid#myotherid id="default"
 * @autor   : Stefan Hövelmanns - hvlmnns.de
 * @License : MIT
 * @package Caramel
 */
class PluginIds extends Models\Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 4;
    }


    /**
     * @param Node $node
     * @return Node
     * @throws \Exception
     */
    public function process($node)
    {
        $tag = $node->get("tag.tag");
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
     * @param Node   $node
     * @param string $tag
     * @param array  $ids
     * @return mixed
     */
    private function updateTag($node, $tag, $ids)
    {
        foreach (explode(" ", $ids) as $id) {
            $tag = str_replace('#' . $id, "", $tag);
        }

        if (trim($tag) == '') {
            $tag = $this->createNewTag($node);
        }

        $node->set("tag.tag", $tag);
        $node->set("tag.opening.tag", $tag);
        $node->set("tag.closing.tag", $tag);

        return $node;
    }


    /**
     * @param Node  $node
     * @param array $ids
     * @return Node
     * @throws \Exception
     */
    private function setAttribute($node, $ids)
    {
        $attributes = $node->get("attributes");
        if ($attributes == "") {
            $attributes = " id='" . $ids . "'";
        } else {
            preg_match("/id=(\"|\').*?(\"|\')/", $attributes, $id);
            $id = $id[0];
            if ($id) {
                $toReplace  = $id;
                $idEnd      = substr($id, -1);
                $id         = substr($id, 0, strlen($id) - 1);
                $id         = $id . ' ' . $ids . $idEnd;
                $attributes = str_replace($toReplace, $id, $attributes);
            } else {
                $attributes = " id='" . $ids . "' " . $attributes;
            }
        }
        $node->set("attributes", $attributes);

        return $node;
    }


    /**
     * @param Node $node
     * @return string
     * @throws \Exception
     */
    private function createNewTag($node)
    {
        # check if tag is empty now
        # if so create div or span,
        # depending if we have a block or inline parent element
        $inline = false;
        if ($node->has("parent")) {
            $parentTag = $node->get("parent")->get("tag.tag");
            $inline    = in_array($parentTag, $this->caramel->config()->get("inline_elements"));
        }
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}