<?php

namespace Underware\Languages\Core;


use Underware\Engine\Structs\Language\Language;
use Underware\Languages\Core\Nodes\BlockNode;
use Underware\Languages\Core\Nodes\ExtendNode;
use Underware\Languages\Core\Nodes\UseNode;
use Underware\Languages\Core\Nodes\VariableNode;
use Underware\Languages\Core\Plugins\ExtendPlugin;
use Underware\Languages\Core\Plugins\VariablesPlugin;


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
     * register core nodes
     */
    private function registerNodes()
    {
        $this->Instance->EventManager()->register("lexer.node.use", new UseNode());
        $this->Instance->EventManager()->register("lexer.node.Extends", new ExtendNode());
        $this->Instance->EventManager()->register("lexer.node.block", new BlockNode());
        $this->Instance->EventManager()->register("lexer.node.variable", new VariableNode());
    }


    /**
     * register core plugins
     */
    private function registerPlugins()
    {
        $this->Instance->EventManager()->register("plugin.output.variables", new VariablesPlugin());
        $this->Instance->EventManager()->register("plugin.dom.extend", new ExtendPlugin());
    }
}