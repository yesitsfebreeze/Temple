<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;
use Temple\Plugins\Plugins;


/**
 * Class Parser
 *
 * @package Temple
 */
class Parser extends DependencyInstance
{

    /** @var  Plugins Plugins */
    protected $Plugins;


    public function dependencies()
    {
        return array(
            "Plugins/Plugins" => "Plugins"
        );
    }


    /**
     * @param $dom
     * @return string
     */
    public function parse($dom)
    {

        # TODO: plugins
        $output = $this->createOutput($dom);
        return $output;
    }


    /**
     * merges the nodes into the final content
     *
     * @param Dom|BaseNode $dom
     * @return mixed
     * @throws TempleException
     */
    private function createOutput($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $dom->get("nodes");
        foreach ($nodes as $node) {
            /** @var BaseNode $node */
            # open the tag
            $output = $this->openTag($node, $output);

            $output = $this->appendContent($node, $output);

            # recursively iterate over the children
            $output = $this->createChildren($node, $output);

            # close the tag
            $output = $this->closeTag($node, $output);

        }

        if (trim($output) == "") return false;

        return $output;
    }


    /**
     * opens the tag
     *
     * @param string   $output
     * @param BaseNode $node
     * @return string
     */
    private function openTag($node, $output)
    {
        if ($node->get("info.display") && $node->get("tag.display") && $node->get("tag.opening.display")) {
            $output .= $node->get("tag.opening.before");
            $output .= $node->get("tag.opening.tag");
            $output = $this->createAttributes($node, $output);
            $output .= $node->get("tag.opening.after");
        }

        return $output;
    }


    /**
     * creates the node attributes
     *
     * @param string   $output
     * @param BaseNode $node
     * @return string
     */
    private function createAttributes($node, $output)
    {
        if (!$node->has("attributes")) {
            return $output;
        }

        $attributes = $node->get("attributes");

        if (sizeof($attributes) == 0) {
            return $output;
        }

        if ($node->get("tag.opening.tag") != "") {
            $output .= " ";
        }

        foreach ($attributes as $attribute) {
            if (isset($attribute["name"])) {
                $output .= $attribute["name"];
                if (isset($attribute["value"])) {
                    $output .= "=";
                    $output .= $attribute["value"];
                }
                $output .= " ";
            }
        }

        return $output;
    }


    /**
     * appends the node content to the output
     *
     * @param string   $output
     * @param BaseNode $node
     * @return string
     */
    private function appendContent($node, $output)
    {
        if ($node->has("content")) {
            $content = $node->get("content");
            if (is_string($content)) {
                $output .= $content;
            }
        }

        return $output;
    }


    /**
     * creates the children node output
     *
     * @param string   $output
     * @param BaseNode $node
     * @return string
     * @throws TempleException
     */
    private function createChildren($node, $output)
    {
        if ($node->has("children")) {
            if ($node->get("info.selfclosing")) {
                throw new TempleException("You can't have children in an " . $node->get("tag.tag") . "!", $node->get("file"), $node->get("line"));
            }

            $children = new Dom();
            $children->set("nodes", $node->get("children"));
            $output .= $this->createOutput($children);
        }

        return $output;
    }


    /**
     * closes the tag
     *
     * @param string   $output
     * @param BaseNode $node
     * @return string
     */
    private function closeTag($node, $output)
    {

        if ($node->get("info.display") && $node->get("tag.closing.display") && $node->get("tag.display")) {
            if (!$node->get("info.selfclosing")) {
                $output .= $node->get("tag.closing.before");
                $output .= $node->get("tag.closing.tag");
                $output .= $node->get("tag.closing.after");
            }
        }

        return $output;
    }

}