<?php


namespace Pavel\Nodes;


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
            $line  = $args[0];
            $infos = $args[1];
            $node  = new Node($Instance->Config());
            $node  = $node->createNode($line, $infos);

            return $node;
        } else {
            return $args;
        }

    }
}