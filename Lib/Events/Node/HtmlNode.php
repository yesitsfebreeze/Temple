<?php


namespace Pavel\Events\Node;


use Pavel\EventManager\Event;
use Pavel\Instance;
use Pavel\Models\BaseNode;
use Pavel\Models\HtmlNode as Node;


/**
 * Class HtmlNode
 *
 * @package Pavel\Event\Nodes
 */
class HtmlNode extends Event
{

    /**
     * @param mixed    $args
     * @param Instance $Instance
     *
     * @return \Pavel\Models\BaseNode|Node
     */
    public function dispatch($args, Instance $Instance)
    {

        if (!$args instanceof BaseNode) {
            $node = new Node($Instance->Config());
            $node = $node->createNode($args);

            return $node;
        } else {
            return $args;
        }

    }
}