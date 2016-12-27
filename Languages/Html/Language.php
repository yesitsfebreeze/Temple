<?php

namespace Temple\Languages\Html;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Languages\BaseLanguage;
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
use Temple\Languages\Html\Services\VariableCache;


/**
 * this is the default language
 * it renders to a mix of html and php
 * Class BaseLanguage
 *
 * @package Temple\Languages\Html
 */
class Language extends BaseLanguage
{

    /** @var  EventManager $EventManager */
    private $EventManager;



    /**
     * registers the nodes for the language
     */
    public function register()
    {
        $this->registerNodes();
        $this->registerModifiers();
        $this->registerPlugins();
    }

    /**
     * registers the VariableCache
     */
    public function registerVariableCache()
    {
        return new VariableCache();
    }



    /**
     * registers all nodes for the html language
     */
    private function registerNodes()
    {
        $this->subscribe("node.include", new IncludeNode());
        $this->subscribe("node.plain", new PlainNode());
        $this->subscribe("node.html", new HtmlNode());
        $this->subscribe("node.comment", new CommentNode());
        $this->subscribe("node.foreach", new ForeachNode());
        $this->subscribe("node.if", new IfNode());
    }


    /**
     * registers all plugins for the html language
     */
    private function registerPlugins()
    {
        $this->subscribe("plugin.variableNode.variableReturn", new VariableReturnPlugin());
        $this->subscribe("plugin.dom.cleanComments", new CleanCommentsPlugin());
        $this->subscribe("plugin.output.cleanPhpTags", new CleanPhpTagsPlugin());
    }


    /**
     * registers all variable modifiers
     */
    private function registerModifiers()
    {
        $this->subscribe("modifier.sizeof", new SizeofModifier());
    }


}