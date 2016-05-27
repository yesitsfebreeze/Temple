<?php

namespace Caramel\Plugins\Html;


use Caramel\Models\NodeModel;
use Caramel\Models\PluginModel;


/**
 * Classes Plugin
 *
 * @description  :converts emmet inspired class definition to actual classes
 * @usage        : div.myclass.myotherclass results in <div class="myclass myotherclass"></div>
 * @author       : Stefan Hövelmanns - hvlmnns.de
 * @License      : MIT
 * @package      Caramel
 */
class Classes extends PluginModel
{


    /**
     * @return int;
     */
    public function position()
    {
        return 3;
    }


    /**
     * @param NodeModel $node
     * @return NodeModel
     * @throws \Exception
     */
    public function process(NodeModel $node)
    {
        $tag     = $node->get("tag.tag");
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
     * @param NodeModel $node
     * @param string    $tag
     * @param array     $classes
     * @return mixed
     */
    private function updateTag(NodeModel $node, $tag, $classes)
    {
        foreach (explode(" ", $classes) as $class) {
            $tag = str_replace('.' . $class, "", $tag);
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
     * @param NodeModel $node
     * @param array     $classes
     * @return NodeModel
     * @throws \Exception
     */
    private function setAttribute(NodeModel $node, $classes)
    {
        /** @var NodeModel $node */
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
     * @param NodeModel $node
     * @return string
     * @throws \Exception
     */
    private function createNewTag(NodeModel $node)
    {
        # check if tag is empty now
        # if so create div or span,
        # depending if we have a block or inline parent element
        $inline = false;
        if ($node->has("parent")) {
            $parentTag = $node->get("parent")->get("tag.tag");
            $inline    = in_array($parentTag, $this->configService->get("inline_elements"));
        }
        if ($inline) {
            $tag = "span";
        } else {
            $tag = "div";
        }

        return $tag;
    }

}