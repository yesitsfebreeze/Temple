<?php

namespace Temple\Languages\Html;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Structs\Language\Language;
use Temple\Languages\Html\Modifiers\SizeofModifier;
use Temple\Languages\Html\Nodes\CommentNode;
use Temple\Languages\Html\Nodes\ForeachNode;
use Temple\Languages\Html\Nodes\HtmlNode;
use Temple\Languages\Html\Nodes\IfNode;
use Temple\Languages\Html\Nodes\IncludeNode;
use Temple\Languages\Html\Nodes\PlainNode;
use Temple\Languages\Html\Nodes\VariableNode;
use Temple\Languages\Html\Plugins\CleanCommentsPlugin;
use Temple\Languages\Html\Plugins\CleanPhpTagsPlugin;
use Temple\Languages\Html\Plugins\VariableReturnPlugin;
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

    /** @var  EventManager $EventManager */
    private $EventManager;

    /**
     * register the nodes for the language
     */
    public function register()
    {
        $this->EventManager = $this->Instance->EventManager();
        $this->registerNodes();
        $this->registerModifiers();
        $this->registerPlugins();
    }


    /**
     * registers all nodes for the html language
     */
    private function registerNodes()
    {
        $this->EventManager->register("node.include", new IncludeNode());
        $this->EventManager->register("node.variable", new VariableNode());
        $this->EventManager->register("node.plain", new PlainNode());
        $this->EventManager->register("node.html", new HtmlNode());
        $this->EventManager->register("node.comment", new CommentNode());
        $this->EventManager->register("node.foreach", new ForeachNode());
        $this->EventManager->register("node.if", new IfNode());
    }


    /**
     * registers all plugins for the html language
     */
    private function registerPlugins()
    {
        $this->EventManager->register("plugin.nodeOutput.variables", new VariablesPlugin());
        $this->EventManager->register("plugin.output.variables", new VariablesPlugin());
        $this->EventManager->register("plugin.variableNode.variableReturn", new VariableReturnPlugin());
        $this->EventManager->register("plugin.dom.cleanComments", new CleanCommentsPlugin());
        $this->EventManager->register("plugin.output.cleanPhpTags", new CleanPhpTagsPlugin());
    }


    /**
     * register all variable modifiers
     */
    private function registerModifiers()
    {
        $this->EventManager->register("modifier.sizeof",new SizeofModifier());
    }


}