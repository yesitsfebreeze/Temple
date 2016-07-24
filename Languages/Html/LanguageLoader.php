<?php

namespace Underware\Languages\Html;


use Underware\Engine\Structs\Language\Language;
use Underware\Languages\Html\Nodes\HtmlNode;


class LanguageLoader extends Language
{

    /**
     * register the nodes for the language
     */
    public function register()
    {
        $this->Instance->EventManager()->register("lexer.node.html", new HtmlNode());
    }
}