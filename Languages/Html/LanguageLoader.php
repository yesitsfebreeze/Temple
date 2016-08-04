<?php

namespace Temple\Languages\Html;


use Temple\Engine\Structs\Language\Language;
use Temple\Languages\Html\Plugins\CleanCommentsPlugin;
use Temple\Languages\Html\Nodes\CommentNode;
use Temple\Languages\Html\Nodes\ForeachNode;
use Temple\Languages\Html\Nodes\HtmlNode;
use Temple\Languages\Html\Nodes\PlainNode;


class LanguageLoader extends Language
{

    /**
     * register the nodes for the language
     */
    public function register()
    {
        $this->registerNodes();
        $this->registerPlugins();
    }

    /**
     * registers all nodes for the html language
     */
    private function registerNodes()
    {
        $this->Instance->EventManager()->register("lexer.node.html", new HtmlNode());
        $this->Instance->EventManager()->register("lexer.node.comment", new CommentNode());
        $this->Instance->EventManager()->register("lexer.node.foreach", new ForeachNode());
        $this->Instance->EventManager()->register("lexer.node.plain", new PlainNode());
    }

    /**
     * registers all plugins for the html language
     */
    private function registerPlugins()
    {
        $this->Instance->EventManager()->register("plugin.dom.cleanComments", new CleanCommentsPlugin());
    }
}