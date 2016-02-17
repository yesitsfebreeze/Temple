<?php

namespace Caramel;


/**
 *
 * Class Caramel_Plugin_Plain
 *
 * @purpose: converts content of an within the plain tag into plain text
 * @usage:
 *
 *      - my text
 *          another text
 *
 *      - or you can
 *      - write like this
 *
 *      -- this text has no trailing space, which is otherwise added by default
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Plain extends PluginBase
{

    /** @var int $position */
    protected $position = 23;

    /**
     * @param Storage $node
     * @return Storage
     * @throws \Exception
     */
    public function process($node)
    {

        if ($node->get("tag")[0] == "-") {

            # check if we want to use the trailing space
            if ($node->get("tag") == "--") {
                # no
                $this->convertPlain($node, false);
            } else {
                # yes
                $this->convertPlain($node, true);
            }

        } else if ($node->has("parent")) {

            # convert nested elements

            /** @var Storage $parent */
            $parent = $node->get("parent");
            if ($parent->has("is_plain")) {
                # check if we want to use the trailing space
                if ($parent->has("is_plain_without_space")) {
                    # no
                    $this->convertPlain($node, false, true);
                } else {
                    # yes
                    $this->convertPlain($node, true, true);
                }
            }
        }

        # always return node
        return $node;
    }


    /**
     * @param $node
     * @param $space
     * @param bool $child
     * @return Storage
     */
    private function convertPlain($node, $space, $child = false)
    {
        /** @var Storage $node */
        $node->set("indent", 0);
        # adjust opening tag
        $node->set("start/prefix", "");

        # only remove the tag if we are not a child
        if (!$child) {
            $node->set("start/tag", "");
        } else {
            # add the now missing space to our first word
            $node->set("start/tag", $node->get("start/tag") . " ");
        }

        $node->set("start/postfix", "");
        # adjust closing tag
        $node->set("end/display", false);

        if ($space) {
            # use the trailing space
            $space = " ";
        } else {
            # don't use the trailing space
            $node->set("is_plain_without_space", true);
            $space = "";
        }

        $node->set("attributes", trim(substr($node->get("attributes"), 2)) . $space);

        $node->set("is_plain", true);

        return $node;
    }

}