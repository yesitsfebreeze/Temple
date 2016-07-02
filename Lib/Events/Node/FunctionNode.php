<?php


namespace Pavel\Events\Node;


use Pavel\EventManager\Event;
use Pavel\Instance;
use Pavel\Models\BaseNode;
use Pavel\Models\FunctionNode as Node;


/**
 * Class FunctionNode
 *
 * @package Pavel\Event\Nodes
 */
class FunctionNode extends Event
{

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
            if ($identifier == "+") {
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