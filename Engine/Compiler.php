<?php

namespace WorkingTitle\Engine;


use WorkingTitle\Engine\EventManager\EventManager;
use WorkingTitle\Engine\Exception\Exception;
use WorkingTitle\Engine\InjectionManager\Injection;
use WorkingTitle\Engine\Structs\Dom;
use WorkingTitle\Engine\Structs\Node\Node;


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