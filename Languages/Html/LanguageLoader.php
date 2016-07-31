<?php

namespace Underware\Languages\Html;


use Underware\Engine\Structs\Language\Language;
use Underware\Languages\Html\Nodes\CommentNode;
use Underware\Languages\Html\Nodes\ForeachNode;
use Underware\Languages\Html\Nodes\HtmlNode;
use Underware\Languages\Html\Nodes\PlainNode;


class LanguageLoader extends Language
{

    /**
     * register the nodes for the language
     */
    public function register()
    {
        $this->Instance->EventManager()->register("lexer.node.html", new HtmlNode());
        $this->Instance->EventManager()->register("lexer.node.comment", new CommentNode());
        $this->Instance->EventManager()->register("lexer.node.foreach", new ForeachNode());
        $this->Instance->EventManager()->register("lexer.node.plain", new PlainNode());
    }
}