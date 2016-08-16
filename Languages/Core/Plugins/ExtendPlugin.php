<?php

namespace Temple\Languages\Core\Plugins;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Node\Node;
use Temple\Languages\Core\Nodes\BlockNode;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class ExtendPlugin extends Event
{


    /**
     * @param Dom $Dom
     *
     * @return Dom
     */
    public function dispatch(Dom $Dom)
    {
        return $this->extendDom($Dom);
    }


    /**
     * recursively extends the dom
     *
     * @param Dom $Dom
     *
     * @return Dom
     */
    private function extendDom(Dom $Dom)
    {
        if ($Dom->isExtending()) {
            foreach ($Dom->getNodes() as &$node) {
                $node = $this->iterate($node, $Dom);
            }

            return $this->extendDom($Dom->getParentDom());
        } else {
            return $Dom;
        }
    }


    /**
     * @param Node $node
     * @param Dom  $Dom
     *
     * @return Node
     */
    private function iterate(Node $node, Dom $Dom)
    {
        $node     = $this->extend($node, $Dom);
        $children = $node->getChildren();
        if (sizeof($children) > 0) {
            foreach ($children as &$child) {
                $child = $this->iterate($child, $Dom);
            }
        }

        return $node;
    }


    /**
     * @param Node $node
     * @param Dom  $Dom
     *
     * @return Node
     */
    private function extend(Node $node, Dom $Dom)
    {

        if ($node->getTag() == "block") {
            /** @var BlockNode $node */
            $name = $node->getBlockName();
            /** @var Dom $parentDom */
            $parentDom = $Dom->getParentDom();
            /** @var BlockNode $block */
            $block  = $parentDom->getBlock($name);
            $method = $node->getBlockMethod();
            if ($method == "before") {
                $children = $block->getChildren();
                $childrenToPrepend = array_reverse($node->getChildren());
                foreach ($childrenToPrepend as $child) {
                    array_unshift($children, $child);
                }
                $block->setChildren($children);
            } elseif ($method == "after") {
                $children   = $block->getChildren();
                foreach ($node->getChildren() as $child) {
                    $children[] = $child;
                }
                $block->setChildren($children);
            } elseif ($method == "replace") {
                $block->setShowComment(false);
                $block->setChildren(array($node));
            }

        }

        return $node;
    }

}