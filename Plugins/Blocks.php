<?php

namespace Temple\Plugin;


use Temple\Exception\TempleException;
use Temple\Models\HtmlNode;
use Temple\Models\Plugin;


/**
 * Class PluginExtend
 *
 * @package     Temple
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
            $node->set("tag.display",false);
        };

        return $node;
    }

}