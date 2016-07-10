<?php


namespace Underware\Models\Nodes;


use Underware\EventManager\Event;
use Underware\Models\Nodes\FunctionNodeModel as Node;


/**
 * Class HtmlNode
 *
 * @package Underware\Event\Nodes
 */
class FunctionNodeSubscriber extends Event
{

    /**
     * @param $line
     * @param $infos
     *
     * @return BaseNode|Node
     */
    public function dispatch($line, $infos = null)
    {

        if (!$line instanceof BaseNode) {
            $node  = new Node($this->Instance->Config());
            $node  = $node->createNode($line, $infos);
            return $node;
        } else {
            return $line;
        }

    }
}