<?php

namespace Pavel\Template;


use Pavel\Dependency\DependencyInstance;
use Pavel\EventManager\EventManager;
use Pavel\Exception\Exception;
use Pavel\Models\BaseNode;
use Pavel\Models\Dom;
use Pavel\Models\FunctionNode;
use Pavel\Models\HtmlNode;


/**
 * Class Parser
 *
 * @package Pavel
 */
class Parser extends DependencyInstance
{

    /** @var  Plugins $Plugins */
    protected $Plugins;

    /** @var  EventManager $EventManager */
    protected $EventManager;


    /** @inheritdoc */
    public function dependencies()
    {
        return $this->getDependencies();
    }


    /**
     * returns the finished template content
     *
     * @param $dom
     *
     * @return string
     */
    public function parse($dom)
    {
        $output = $this->createOutput($dom);
        $this->EventManager->notify("plugins.output", $output);

        return $output;
    }


    /**
     * merges the nodes into the final content
     *
     * @param Dom|array $dom
     *
     * @return mixed
     * @throws Exception
     */
    private function createOutput($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $dom->get("nodes");
        /** @var BaseNode $node */
        foreach ($nodes as $node) {
            $node->set("dom", $dom);

            if ($node->isFunction()) {
                /** @var FunctionNode $node */
                $node = $this->EventManager->notify("plugins.node.functions", $node);
            } else {
                /** @var HtmlNode $node */
                $node = $this->EventManager->notify("plugins.node.process", $node);
            }

            if ($node->get("info.isPlain")) {

                $output .= " " . trim($node->get("info.plain"));

            } else {

                /** @var BaseNode $node */
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
     *
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
     *
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

            $trim = false;
            if ($node->has("info.attributes.trim")) {
                $trim = $node->get("info.attributes.trim");
            }

            if (!$trim) {
                $attrs = " ";
            } else {
                $attrs = "";
            }

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
     *
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
     *
     * @return string
     * @throws Exception
     */
    private function createChildren($node, $output)
    {
        if ($node->has("children")) {
            if ($node->get("info.selfClosing")) {
                throw new Exception("You can't have children in an " . $node->get("tag.definition") . "!", $node->get("file"), $node->get("line"));
            }

            $children = new Dom();
            if ($node->has("dom")) {
                /** @var Dom $oldDom */
                $oldDom = $node->get("dom");
                if ($oldDom->has("info")) {
                    $oldDom->get("info");
                    $children->set("info", $oldDom->get("info"));
                }
            }
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
     *
     * @return string
     */
    private function closeTag($node, $output)
    {

        if ($node->get("info.display") && $node->get("tag.closing.display") && $node->get("tag.display")) {
            if (!$node->get("info.selfClosing")) {
                $output .= $node->get("tag.closing.before");
                $output .= $node->get("tag.closing.definition");
                $output .= $node->get("tag.closing.after");
            }
        }

        return $output;
    }

}