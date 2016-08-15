<?php

namespace Temple\Languages\Html;


use Temple\Engine\Structs\Language\Language;
use Temple\Languages\Html\Nodes\CommentNode;
use Temple\Languages\Html\Nodes\ForeachNode;
use Temple\Languages\Html\Nodes\HtmlNode;
use Temple\Languages\Html\Nodes\IfNode;
use Temple\Languages\Html\Nodes\PlainNode;
use Temple\Languages\Html\Nodes\VariableNode;
use Temple\Languages\Html\Plugins\CleanCommentsPlugin;
use Temple\Languages\Html\Plugins\CleanPhpTagsPlugin;
use Temple\Languages\Html\Plugins\VariablesPlugin;


/**
 * this is the default language
 * it renders to a mix of html and php
 *
 * Class LanguageLoader
 *
 * @package Temple\Languages\Html
 */
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
        $this->Instance->EventManager()->register("node.variable", new VariableNode());
        $this->Instance->EventManager()->register("node.html", new HtmlNode());
        $this->Instance->EventManager()->register("node.comment", new CommentNode());
        $this->Instance->EventManager()->register("node.foreach", new ForeachNode());
        $this->Instance->EventManager()->register("node.plain", new PlainNode());
        $this->Instance->EventManager()->register("node.if", new IfNode());
    }


    /**
     * registers all plugins for the html language
     */
    private function registerPlugins()
    {
        $this->Instance->EventManager()->register("plugin.dom.cleanComments", new CleanCommentsPlugin());
        $this->Instance->EventManager()->register("plugin.output.cleanPhpTags", new CleanPhpTagsPlugin());
        $this->Instance->EventManager()->register("plugin.nodeoutput.variables", new VariablesPlugin());
        $this->Instance->EventManager()->register("plugin.output.variables", new VariablesPlugin());
    }


}