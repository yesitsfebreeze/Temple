<?php

namespace Temple\Languages\Html\Plugins;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Node\Node;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class CleanCommentsPlugin extends Event
{
    /**
     * defines the tags which can't contain comments
     *
     * @var array
     */
    private $tags = array("title");


    /**
     * iterates over all nodes and cleans the comments if we have a matching tag
     *
     * @param Dom $dom
     *
     * @return mixed
     */
    public function dispatch(Dom $dom)
    {
        $nodes = $dom->getNodes();
        foreach ($nodes as &$node) {
            $node = $this->cleanNode($node);
        }
        $dom->setNodes($nodes);

        return $dom;
    }


    /**
     * cleans the nodes
     *
     * @param Node $node
     * @param bool $showComments
     *
     * @return Node
     */
    public function cleanNode(Node $node, $showComments = true)
    {
        if (in_array($node->getTag(), $this->tags)) {
            $showComments = false;
        }

        if ($node->isCommentNode() && !$showComments) {
            $node->setShowComment(false);
        }


        $children = $node->getChildren();
        if (sizeof($children) > 0) {
            foreach ($children as &$child) {
                $this->cleanNode($child, $showComments);
            }
        }
        $node->setChildren($children);

        return $node;
    }


}