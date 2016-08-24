<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Exception\Exception;
use Temple\Engine\LanguageConfig;
use Temple\Engine\Structs\Node\Node;


/**
 * Class ExtendNode
 *
 * @package Temple\Languages\Core\Nodes
 */
class IncludeNode extends Node
{

    public function check()
    {
        $this->throwException();
        // todo: sample code for default nodes
    }


    public function setup()
    {
        $this->throwException();
        // todo: sample code for default nodes
    }


    public function compile()
    {
        $this->throwException();
        // todo: sample code for default nodes
    }


    private function throwException()
    {
        /** @var LanguageConfig $languageConfig */
        $languageConfig = $this->Dom->getLanguage()->getConfig();
        $name     = $languageConfig->getName();
        throw new Exception(500, "Please implement the %include% node for the %" . $name . "% language");
    }

}