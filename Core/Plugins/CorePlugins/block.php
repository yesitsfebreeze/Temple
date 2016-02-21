<?php

namespace Caramel;


/**
 *
 * Class Caramel__Plugin_Block
 *
 * @purpose: hide all blocks || show all blocks as comments ; depends on configuration
 * @usage: automatic
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginBlock extends FunctionPlugin
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

        if ($node->get("tag") == "block") {

            if ($this->config->get("show_blocks")) {
                # shows name and namespace attributes as a comment for the block
                $node->set("attributes", " name='" . trim($node->get("attributes")) . "' namespace='" . $node->get("namespace") . "'");
                $node->set("start/prefix", "<!-- ");
                $node->set("start/tag", strtoupper($node->get("start/tag")));
                $node->set("start/postfix", " -->");
                $node->set("end/display", false);
            } else {
                # just hide the blocks
                $node->set("display", false);
            }
        }

        # always return node
        return $node;
    }
}