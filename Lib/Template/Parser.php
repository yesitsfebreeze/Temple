<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;
use Temple\Models\Nodes\FunctionNode;
use Temple\Models\Nodes\HtmlNode;


/**
 * Class Parser
 *
 * @package Temple
 */
class Parser extends DependencyInstance
{

    /** @var  Plugins $Plugins */
    protected $Plugins;


    /**
     * @return array
     */
    public function dependencies()
    {
        return array(
            "Template/Plugins" => "Plugins"
        );
    }


    /**
     * returns the finished template content
     *
     * @param $dom
     * @return string
     */
    public function parse($dom)
    {
        $output = $this->createOutput($dom);
        $output = $this->Plugins->processOutput($output);

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
        /** @var BaseNode $node */
        foreach ($nodes as $node) {

            if ($node->isFunction()) {
                /** @var FunctionNode $node */
                $node = $this->Plugins->processFunctions($node);
            } else {
                /** @var HtmlNode $node */
                $node = $this->Plugins->process($node);
            }

            if ($node->get("info.isPlain")) {
                $output = trim($node->get("info.plain"));
            } else {
                /** @var BaseNode $node */
                # open the tag
                $output = $this->openTag($node, $output);

                $output = $this->appendContent($node, $output);
            }

            # recursively iterate over the children
            $output = $this->createChildren($node, $output);

            if (!$node->get("info.isPlain")) {
                # close the tag
                $output = $this->closeTag($node, $output);
            }

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
            $output .= $node->get("tag.opening.definition");
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

        $attributes = $node->get("attributes");
        if (is_array($attributes)) {

            if (sizeof($node->get("attributes")) == 0) {
                return $output;
            }

            if ($node->get("tag.opening.definition") != "") {
                $output .= " ";
            }

            $attrs = " ";

            foreach ($attributes as $name => $value) {
                $attrs .= $name;
                if ($value != "") {
                    $attrs .= "='" . $value . "'";
                }
                $attrs .= " ";
            }

            $output .= $attrs;
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
                throw new TempleException("You can't have children in an " . $node->get("tag.definition") . "!", $node->get("file"), $node->get("line"));
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
                $output .= $node->get("tag.closing.definition");
                $output .= $node->get("tag.closing.after");
            }
        }

        return $output;
    }

}