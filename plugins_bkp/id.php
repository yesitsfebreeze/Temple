<?php

namespace Caramel;


/**
 *
 * Class Caramel_Plugin_Id
 *
 * @purpose: converts emmet inspired id definition to actual ids
 * @usage: div#myid#myotherid id="default"
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Id extends PluginBase
{

    /** @var int $position */
    protected $position = 1;

    /**
     * @param Storage $node
     * @return Storage
     * @throws \Exception
     */
    public function process($node)
    {
        $tag = $node->get("tag");
        $ids = explode("#", $tag);
        if (sizeof($ids) > 1) {
            $ids  = $this->getIds($tag, $ids);
            $node = $this->updateTag($node, $tag, $ids);
            $node = $this->setAttribute($node, $ids);
        }

        # always return node
        return $node;
    }

    /**
     * @param $tag
     * @param $ids
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
     * @param $node
     * @param $tag
     * @param $ids
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

        $node->set("start/tag", $tag);
        $node->set("end/tag", $tag);
        $node->set("tag", $tag);

        return $node;
    }

    /**
     * @param $node
     * @param $ids
     * @return Storage
     * @throws \Exception
     */
    private function setAttribute($node, $ids)
    {
        /** @var Storage $node */
        $attributes = $node->get("attributes");
        if ($attributes == "") {
            $attributes = " id='" . $ids . "'";
        } else {
            preg_match("/id=(\"|\').*?(\"|\')/", $attributes, $id);
            $id      = $id[0];
            if ($id) {
                $toReplace  = $id;
                $idEnd   = substr($id, -1);
                $id      = substr($id, 0, strlen($id) - 1);
                $id      = $id . ' ' . $ids . $idEnd;
                $attributes = str_replace($toReplace, $id, $attributes);
            } else {
                $attributes = " id='" . $ids . "' " . $attributes ;
            }
        }
        $node->set("attributes", $attributes);

        return $node;
    }

    /**
     * @param $node
     * @return string
     * @throws \Exception
     */
    private function createNewTag($node)
    {
        # check if tag is empty now
        # if so create div or span,
        # depending if we have a block or inline parent element
        $parentTag = $node->get("parent")->get("tag");
        $inline    = in_array($parentTag, $this->config->get("inline_elements"));
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}