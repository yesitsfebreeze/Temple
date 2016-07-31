<?php

namespace WorkingTitle\Languages\Html;


use WorkingTitle\Engine\Structs\Language\Language;
use WorkingTitle\Languages\Html\Nodes\CommentNode;
use WorkingTitle\Languages\Html\Nodes\ForeachNode;
use WorkingTitle\Languages\Html\Nodes\HtmlNode;
use WorkingTitle\Languages\Html\Nodes\PlainNode;


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