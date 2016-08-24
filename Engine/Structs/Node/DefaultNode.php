<?php

namespace Temple\Engine\Structs\Node;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Languages\LanguageConfig;


/**
 * Class DefaultNode
 *
 * @package Temple\Engine\Structs
 */
class DefaultNode extends Node
{

    /** @inheritdoc */
    public function check()
    {
        return true;
    }


    /**
     * converts the line into a node
     *
     * @return Node|bool
     */
    public function setup()
    {
        return $this;
    }


    /**
     * @throws Exception
     */
    public function compile()
    {
        /** @var LanguageConfig $language */
        $languageConfig = $this->getDom()->getLanguage()->getConfig();
        $languageName = $languageConfig->getName();
        throw new Exception(400, "The %" . $this->getTag() . "% node for the language %" . $languageName . "% is not defined!", $this->getDom()->getFile(), $this->getLine());
    }


}