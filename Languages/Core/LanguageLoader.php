<?php

namespace Rite\Languages\Core;


use Rite\Engine\Structs\Language\Language;
use Rite\Languages\Core\Nodes\BlockNode;
use Rite\Languages\Core\Nodes\ExtendNode;
use Rite\Languages\Core\Nodes\UseNode;
use Rite\Languages\Core\Nodes\VariableNode;
use Rite\Languages\Core\Plugins\ExtendPlugin;
use Rite\Languages\Core\Plugins\VariablesPlugin;


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