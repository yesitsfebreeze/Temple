<?php

namespace Pavel\Events\Plugin;


use Pavel\Exception\Exception;
use Pavel\Models\HtmlNode;
use Pavel\Models\Plugin;


/**
 * Class Bricks
 *
 * @package Pavel\Event\Plugins
 */
class Bricks extends Plugin
{

    /** @var array $methods */
    private $methods = array("before", "after", "wrap", "replace");


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
        if ($node->get("tag.definition") == "brick") {
            $this->hideBrick($node);
            $node = $this->modifyBrick($node);
        };

        return $node;
    }
 

    /**
     * @param HtmlNode $node
     */
    private function hideBrick(HtmlNode $node)
    {

        if ($this->Instance->Config()->get("template.comments.bricks")) {
            $node->set("tag.opening.before", "<!-- ");
            $node->set("tag.opening.after", " file: " . $node->get("info.file") . "  --!>");

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

                if (isset($domBricks[ $name ])) {
                    $methods = $domBricks[ $name ];
                    foreach ($methods as $method => $bricks) {
                        if ($method == "before") {
                            $node = $this->modifyBrickBefore($node, $bricks);
                        } elseif ($method == "after") {
                            $node = $this->modifyBrickAfter($node, $bricks);
                        } elseif ($method == "wrap") {
                            $node = $this->modifyBrickWrap($node, $bricks);
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
    private function modifyBrickAfter(HtmlNode $node, $bricks)
    {
        $children = $node->get("children");
        foreach ($bricks as $brick) {
            $this->setProcessed($brick);
            $children[] = $brick;
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
        $last = reset(array_reverse($bricks));
        $this->setProcessed($last);
        $node->set("children", array($last));

        return $node;

    }


    /**
     * inserts bricks after the extended brick
     *
     * @param HtmlNode $node
     * @param array    $bricks
     *
     * @return HtmlNode $node
     * @throws Exception
     */
    private function modifyBrickWrap(HtmlNode $node, $bricks)
    {
        foreach ($bricks as $brick) {
            $this->checkWrapBrick($brick);
            $deepest = $this->getWrapBrick($brick);
            $deepest->set("children", array($node));
            $node = $brick;
            $this->setProcessed($brick);
        }

        $this->hideBrick($node);

        return $node;
    }


    /**
     * @param HtmlNode $brick
     *
     * @return HtmlNode
     * @throws Exception
     */
    private function getWrapBrick(HtmlNode $brick)
    {

        if (isset($brick)) {
            $children = $brick->get("children");
            if (isset($children[0])) {
                $brick = $this->getWrapBrick($children[0]);
            }
        }

        return $brick;
    }


    /**
     * checks if the given node is valid
     *
     * @param HtmlNode $brick
     * @param bool     $deep
     *
     * @return HtmlNode
     * @throws Exception
     */
    private function checkWrapBrick(HtmlNode $brick, $deep = false)
    {

        $children = $brick->get("children");
        $first    = reset($children);

        if ($deep && sizeof($children) == 0) {
            return true;
        }

        if (sizeof($children) != 1) {
            throw new Exception("Wrap brick can only have one child!", $brick->get("info.file"), $brick->get("info.line"));
        }

        if (!$first->get("info.plugins")) {
            throw new Exception("Cant wrap brick with that type of node!", $brick->get("info.file"), $brick->get("info.line"));
        }

        if ($first->get("info.selfClosing")) {
            throw new Exception("Cant wrap a self closing element!", $brick->get("info.file"), $brick->get("info.line"));
        }

        $this->checkWrapBrick($first, true);
    }

}