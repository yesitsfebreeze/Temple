<?php


namespace Underware\Nodes;


use Underware\EventManager\Event;
use Underware\Instance;
use Underware\Models\BaseNode;
use Underware\Models\HtmlNode as Node;


/**
 * Class HtmlNode
 *
 * @package Underware\Event\Nodes
 */
class HtmlNode extends Event
{

    /**
     * @param mixed    $args
     * @param Instance $Instance
     *
     * @return \Underware\Models\BaseNode|Node
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