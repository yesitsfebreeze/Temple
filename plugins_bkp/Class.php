<?php

namespace Caramel;


/**
 *
 * Class Caramel_Plugin_Class
 *
 * @purpose: converts emmet inspired class definition to actual classes
 * @usage: div.myid.myotherid class="default"
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Class extends PluginBase
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
        $tag     = $node->get("tag");
        $classes = explode(".", $tag);
        if (sizeof($classes) > 1) {
            $classes = $this->getClasses($tag, $classes);
            $node    = $this->updateTag($node, $tag, $classes);
            $node    = $this->setAttribute($node, $classes);
        }

        # always return node
        return $node;
    }

    /**
     * @param $tag
     * @param $classes
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
     * @param $node
     * @param $tag
     * @param $classes
     * @return mixed
     */
    private function updateTag($node, $tag, $classes)
    {
        foreach (explode(" ", $classes) as $class) {
            $tag = str_replace('.' . $class, "", $tag);
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
     * @param $classes
     * @return Storage
     * @throws \Exception
     */
    private function setAttribute($node, $classes)
    {
        /** @var Storage $node */
        $attributes = $node->get("attributes");
        if ($attributes == "") {
            $attributes = " class='" . $classes . "'";
        } else {
            preg_match("/class=(\"|\').*?(\"|\')/", $attributes, $class);
            $class = $class[0];
            if ($class) {
                $toReplace  = $class;
                $classEnd   = substr($class, -1);
                $class      = substr($class, 0, strlen($class) - 1);
                $class      = $class . ' ' . $classes . $classEnd;
                $attributes = str_replace($toReplace, $class, $attributes);
            } else {
                $attributes = " class='" . $classes . "' " . $attributes;
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