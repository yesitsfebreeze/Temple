<?php


namespace Underware\Nodes;


use Underware\EventManager\Event;
use Underware\Instance;
use Underware\Models\BaseNode;
use Underware\Models\FunctionNode as Node;


/**
 * Class FunctionNode
 *
 * @package Underware\Event\Nodes
 */
class FunctionNode extends Event
{

    private $identifier = "+";

    /**
     * @param mixed    $args
     * @param Instance $Instance
     *
     * @return mixed|BaseNode|Node
     */
    public function dispatch($args, Instance $Instance)
    {

        if (!$args instanceof BaseNode) {
            $line       = $args[0];
            $infos      = $args[1];
            $identifier = trim($line)[0];
            if ($identifier == $this->identifier) {
                $node = new Node($Instance->Config());
                $node = $node->createNode($line, $infos);

                return $node;
            } else {
                return $args;
            }
        } else {
            return $args;
        }
        
    }
}