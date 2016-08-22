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
     * @param Dom $dom
     *
     * @return string
     */
    public function compile(Dom $dom)
    {
        $this->Language       = $dom->getLanguage();
        $this->languagePrefix = "language." . $this->Language->getName() . ".";
        $dom                  = $this->EventManager->dispatch($this->languagePrefix . "plugin.dom", $dom);
        $output               = $this->createOutput($dom);
        $output               = $this->EventManager->dispatch($this->languagePrefix . "plugin.output", $output);

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
            $nodeOutput = $node->compile();
            $nodeOutput = $this->EventManager->dispatch($this->languagePrefix . "plugin.nodeOutput", array($nodeOutput, $node));

            if (!is_string($nodeOutput) && !is_array($nodeOutput)) {
                throw new Exception(600, "There went something wrong with the %" . $this->languagePrefix . "plugin.nodeOutput% event!");
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