<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Language;
use Temple\Engine\Structs\Node\Node;


/**
 * Class Compiler
 *
 * @package Project
 */
class Compiler extends Injection
{

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var  Language $Language */
    private $Language;

    /** @var  string $languagePrefix */
    private $languagePrefix;


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
     * @param Dom $Dom
     *
     * @return string
     */
    public function compile(Dom $Dom)
    {
        $language = $Dom->getLanguage()->getName();
        $Dom                  = $this->EventManager->dispatch($language,"plugin.dom", $Dom);
        $output               = $this->createOutput($Dom);
        $output               = $this->EventManager->dispatch($language,"plugin.output", $output);
        return $output;
    }


    /**
     * merges the nodes into the final content
     *
     * @param Dom|array $Dom
     *
     * @return mixed
     * @throws Exception
     */
    private function createOutput($Dom)
    {
        # temp variable for the output
        $output = '';
        $nodes  = $Dom->getNodes();
        /** @var Node $node */
        foreach ($nodes as $node) {
            $node->setDom($Dom);
            $nodeOutput = $node->compile();
            $language = $Dom->getLanguage()->getName();
            $nodeOutput = $this->EventManager->dispatch($language,"plugin.nodeOutput", array($nodeOutput, $node));

            if (!is_string($nodeOutput) && !is_array($nodeOutput)) {
                throw new Exception(600, "There went something wrong with the %plugin.nodeOutput% event!");
            }

            if (is_array($nodeOutput)) {
                $nodeOutput = $nodeOutput[0];
            }

            $output .= $nodeOutput;
        }

        if (trim($output) == "") return false;

        return $output;
    }

}