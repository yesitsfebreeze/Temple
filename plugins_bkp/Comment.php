<?php

namespace Caramel;


/**
 *
 * Class Caramel_Plugin_Comment
 *
 * @purpose: converts line to comment with all of its children
 * @usage: # at linestart
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Comment extends PluginBase
{

    /** @var int $position */
    protected $position = 0;

    /**
     * @param Storage $node
     * @return Storage
     * @throws \Exception
     */
    public function process($node)
    {
        if ($node->get("tag")[0] == "#" && ($node->get("tag")[1] == " " || $node->get("tag")[1] == "")) {
            $node->set("process_plugins", false);
            # if we want to hide the comments
            if (!$this->config->get("show_comments")) {
                $node->set("display", false);
                $node->set("children", new Storage());

                return $node;
            }

            # adjust opening tag
            $node->set("start/prefix", "<!-- \n    ");
            $node->set("start/tag", "");
            $node->set("start/postfix", "");

            $node->set("attributes", trim($node->get("attributes")));

            # adjust closing tag
            $node->set("end/prefix", "");
            $node->set("end/tag", "");
            $node->set("end/postfix", "\n -->");

            # set variable to prevent nested comments to break the html
            $node->set("is_comment", true);
        } else if ($node->has("parent")) {
            # ignore nested comments
            /** @var Storage $parent */
            $parent = $node->get("parent");
            if ($parent->has("is_comment")) {
                # set variable to prevent nested comments to break the html on children
                /** @var integer $indent */
                $indent = $node->get("indent");
                $node->set("is_comment", true);
                $node->set("start/prefix", "\n" . str_repeat("    ", $indent));
                $node->set("start/postfix", "");
                $node->set("end/prefix", "");
                $node->set("end/tag", "");
                $node->set("end/postfix", "");
            }
        }

        # always return node
        return $node;
    }

}