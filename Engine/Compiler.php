<?php

namespace Underware\Engine;


use Underware\Engine\EventManager\EventManager;
use Underware\Engine\Exception\Exception;
use Underware\Engine\InjectionManager\Injection;
use Underware\Engine\Structs\Dom;
use Underware\Engine\Structs\Node\Node;


/**
 * Class Compiler
 *
 * @package Project
 */
class Compiler extends Injection
{

    /** @var  EventManager $EventManager */
    protected $EventManager;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/EventManager/EventManager" => "EventManager"
        );
    }


    /**
     * returns the finished template content
     *
     * @param $dom
     *
     * @return string
     */
    public function compile($dom)
    {
        $output = $this->createOutput($dom);
        $output = $this->EventManager->notify("plugin.output", $output);

        return $output;
    }


    /**
     * merges the nodes into the final content
     *
     * @param Dom|array $dom
     *
     * @return mixed
     * @throws Exception
     */
    private function createOutput($dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $dom->getNodes();
        /** @var Node $node */
        foreach ($nodes as $node) {
            $node->setDom($dom);
            $output .= $node->compile();
        }

        if (trim($output) == "") return false;

        return $output;
    }

}