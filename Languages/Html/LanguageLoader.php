<?php

namespace Rite\Languages\Html;


use Rite\Engine\Structs\Language\Language;
use Rite\Languages\Html\Nodes\CommentNode;
use Rite\Languages\Html\Nodes\ForeachNode;
use Rite\Languages\Html\Nodes\HtmlNode;
use Rite\Languages\Html\Nodes\PlainNode;


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