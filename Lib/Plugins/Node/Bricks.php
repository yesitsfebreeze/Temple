<?php

namespace Underware\Plugins\Node;


use Underware\Models\Nodes\HtmlNodeModel;
use Underware\Models\Plugins\NodePlugin;


/**
 * Class Bricks
 *
 * @package Underware\Event\Plugins
 */
class Bricks extends NodePlugin
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
        if ($args instanceof HtmlNodeModel) {
            return ($args->get("tag.definition") == "brick");
        }

        return false;
    }


    /**
     * converts the bricks to comments or completely removes them
     * depending on configuration
     *
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel $node
     */
    public function process($node)
    {
        $this->hideBrick($node);
        $node = $this->modifyBrick($node);

        return $node;
    }


    /**
     * @param HtmlNodeModel $node
     */
    private function hideBrick(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel
     */
    private function modifyBrick(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $brick
     */
    public function setProcessed(HtmlNodeModel $brick)
    {
        $brick->set("info.brick.processed", true);
    }


    /**
     * returns the brick method
     *
     * @param HtmlNodeModel $node
     *
     * @return string $name
     */
    private function getBrickName(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $node
     * @param array    $bricks
     *
     * @return HtmlNodeModel $node
     */
    private function modifyBrickBefore(HtmlNodeModel $node, $bricks)
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
     * @param HtmlNodeModel $node
     * @param array    $bricks
     *
     * @return HtmlNodeModel $node
     */
    private function modifyBrickReplace(HtmlNodeModel $node, $bricks)
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
     * @param HtmlNodeModel $node
     * @param array    $bricks
     *
     * @return HtmlNodeModel $node
     */
    private function modifyBrickAfter(HtmlNodeModel $node, $bricks)
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
     * @param HtmlNodeModel $node
     * @param          $domBricks
     *
     * @return HtmlNodeModel
     */
    private function modifyBrickParent(HtmlNodeModel $node, $domBricks)
    {

        $parent = $this->getParentBrick($node);
        $name   = $this->getBrickName($parent);
        $method = $this->getModifyMethod($parent);
        $brick  = reset(array_reverse($domBricks[ $name ][ $method ]));

        /** @var HtmlNodeModel $parent */
        $parent = $brick->get("info.brick.parent");
        $node->set("children", $parent->get("children"));

        return $node;

    }


    /**
     * returns the closest brick
     *
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel
     */
    private function getParentBrick(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $node
     *
     * @return string $method
     */
    private function getModifyMethod(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $brick
     * @param HtmlNodeModel $node
     * @param bool     $topLevel
     */
    private function setBrickParent(HtmlNodeModel $brick, HtmlNodeModel $node, $topLevel = true)
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