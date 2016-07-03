<?php

namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugin;


/**
 * Class Bricks
 *
 * @package Underware\Event\Plugins
 */
class Bricks extends Plugin
{

    /** @var array $methods */
    private $methods = array("before", "after", "wrap", "replace");


    /**
     * check if we have a brick
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function check($args)
    {
        if ($args instanceof HtmlNode) {
            return ($args->get("tag.definition") == "brick");
        }

        return false;
    }


    /**
     * converts the bricks to comments or completely removes them
     * depending on configuration
     *
     * @param HtmlNode $node
     *
     * @return HtmlNode $node
     */
    public function process($node)
    {
        $this->hideBrick($node);
        $node = $this->modifyBrick($node);

        return $node;
    }


    /**
     * @param HtmlNode $node
     */
    private function hideBrick(HtmlNode $node)
    {

        if ($this->Instance->Config()->get("template.comments.bricks")) {
            $node->set("tag.opening.before", "<!-- ");
            $node->set("tag.opening.after", $node->get("info.relativeFile") . ":" . $node->get("info.line") . " --!>");

            $node->set("tag.closing.before", "");
            $node->set("tag.closing.after", "");
            $node->set("tag.closing.definition", "");
        } else {
            $node->set("tag.display", false);
        }
    }


    /**
     * appends,prepends and replaces the bricks of the new dom
     *
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    private function modifyBrick(HtmlNode $node)
    {
        $processed = false;
        if ($node->has("info.brick.processed")) {
            $processed = $node->get("info.brick.processed");
        }

        if (!$processed) {
            $dom = $node->get("dom");
            if ($dom->has("info.bricks")) {
                $domBricks = $dom->get("info.bricks");
                $name      = $this->getBrickName($node);

                if ($name == "parent") {
                    $node = $this->modifyBrickParent($node, $domBricks);
                } elseif (isset($domBricks[ $name ])) {
                    $methods = $domBricks[ $name ];
                    foreach ($methods as $method => $bricks) {
                        if ($method == "before") {
                            $node = $this->modifyBrickBefore($node, $bricks);
                        } elseif ($method == "after") {
                            $node = $this->modifyBrickAfter($node, $bricks);
                        } elseif ($method == "replace") {
                            $node = $this->modifyBrickReplace($node, $bricks);
                        }
                    }
                }
            }
        }

        return $node;
    }


    /**
     * sets the brick to processed to prevent recursion
     *
     * @param HtmlNode $brick
     */
    public function setProcessed(HtmlNode $brick)
    {
        $brick->set("info.brick.processed", true);
    }


    /**
     * returns the brick method
     *
     * @param HtmlNode $node
     *
     * @return string $name
     */
    private function getBrickName(HtmlNode $node)
    {
        $attributes = $node->get("attributes");

        if (isset($attributes["name"])) {
            $name = $attributes["name"];
        } else {
            $name = implode(" ", array_keys($attributes));
            foreach ($this->methods as $method) {
                $name = preg_replace("/" . $method . "$/", "", $name);
            }
            $name = trim($name);
        }

        return $name;
    }


    /**
     * inserts bricks before the extended brick
     *
     * @param HtmlNode $node
     * @param array    $bricks
     *
     * @return HtmlNode $node
     */
    private function modifyBrickBefore(HtmlNode $node, $bricks)
    {
        $children = $node->get("children");
        foreach ($bricks as $brick) {
            $this->setProcessed($brick);
            $this->setBrickParent($brick, $node);

            array_unshift($children, $brick);
        }
        $node->set("children", $children);

        return $node;
    }


    /**
     * inserts bricks after the extended brick
     *
     * @param HtmlNode $node
     * @param array    $bricks
     *
     * @return HtmlNode $node
     */
    private function modifyBrickReplace(HtmlNode $node, $bricks)
    {
        $brick = reset(array_reverse($bricks));
        $this->setProcessed($brick);
        $this->setBrickParent($brick, $node, false);

        $node->set("children", array($brick));

        return $node;

    }


    /**
     * inserts bricks after the extended brick
     *
     * @param HtmlNode $node
     * @param array    $bricks
     *
     * @return HtmlNode $node
     */
    private function modifyBrickAfter(HtmlNode $node, $bricks)
    {
        $children = $node->get("children");
        foreach ($bricks as $brick) {
            $this->setProcessed($brick);
            $this->setBrickParent($brick, $node);

            $children[] = $brick;
        }
        $node->set("children", $children);

        return $node;
    }


    /**
     * replaces the "brick parent" pattern with the parent brick
     *
     * @param HtmlNode $node
     * @param          $domBricks
     *
     * @return HtmlNode
     */
    private function modifyBrickParent(HtmlNode $node, $domBricks)
    {

        $parent = $this->getParentBrick($node);
        $name   = $this->getBrickName($parent);
        $method = $this->getModifyMethod($parent);
        $brick  = reset(array_reverse($domBricks[ $name ][ $method ]));

        /** @var HtmlNode $parent */
        $parent = $brick->get("info.brick.parent");
        $node->set("children", $parent->get("children"));

        return $node;

    }


    /**
     * returns the closest brick
     *
     * @param HtmlNode $node
     *
     * @return HtmlNode
     */
    private function getParentBrick(HtmlNode $node)
    {
        $parent     = $node->get("info.parent");
        $definition = $parent->get("tag.definition");
        if ($definition != "brick") {
            return $this->getParentBrick($parent);
        } else {
            return $parent;
        }

    }


    /**
     * returns the brick method
     *
     * @param HtmlNode $node
     *
     * @return string $method
     */
    private function getModifyMethod(HtmlNode $node)
    {
        $attributes = $node->get("attributes");
        if (isset($attributes["method"])) {
            $method = $attributes["method"];
        } else {
            $method = array_reverse(array_keys($attributes))[0];
            if (!in_array($method, $this->methods)) {
                $method = "replace";
            }
        }

        return $method;
    }


    /**
     * @param HtmlNode $brick
     * @param HtmlNode $node
     * @param bool     $topLevel
     */
    private function setBrickParent(HtmlNode $brick, HtmlNode $node, $topLevel = true)
    {
        $parent = clone $node;
        if ($topLevel) {
            if ($parent->has("info.brick.parent")) {
                $parent = $parent->get("info.brick.parent");
            };
            if ($brick->has("info.brick.parent")) {
                $parent = $brick->get("info.brick.parent");
            };
        }
        $brick->set("info.brick.parent", $parent);
        $node->set("info.brick.parent", $parent);
    }
}