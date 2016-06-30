<?php

namespace Shift\Plugin;

use Shift\Models\HtmlNode;
use Shift\Models\Plugin;


/**
 * Class PluginExtend
 *
 * @package     Shift
 * @description handles the extending of files and blocks
 * @position    1
 * @author      Stefan Hövelmanns
 * @License     MIT
 */
class Blocks extends Plugin
{

    /**
     * @return int;
     */
    public function position()
    {
        return 1;
    }


    public function isProcessor()
    {
        return true;
    }


    /**
     * converts the blocks to comments or completely removes them
     * depending on configuration
     *
     * @param HtmlNode $node
     * @return HtmlNode $node
     */
    public function process(HtmlNode $node)
    {

        if ($node->get("tag.definition") == "block") {
            $node->set("tag.display", false);
            $node = $this->modifyBlock($node);
        };

        return $node;
    }

    
    /**
     * wraps, prepends, appends or replaces the block
     *
     * @param $node
     * @return mixed
     */
    private function modifyBlock($node)
    {
        $method = $this->getMethod($node);
        return $node;
    }


    /**
     * returns the block handling method
     *
     * @param $node
     * @return string
     */
    private function getMethod($node)
    {
        return "replace";
    }

}