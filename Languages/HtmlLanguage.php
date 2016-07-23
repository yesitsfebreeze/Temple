<?php

namespace Underware\Languages;


use Underware\Engine\EventManager\EventManager;
use Underware\Engine\Structs\Language;
use Underware\Nodes\Html\HtmlNode;


class HtmlLanguage extends Language
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
     * register the nodes for the language
     */
    public function register()
    {
        $this->EventManager->attach("lexer.node", new HtmlNode());
    }
}