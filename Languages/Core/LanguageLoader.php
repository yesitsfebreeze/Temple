<?php

namespace Temple\Languages\Core;


use Temple\Engine\Structs\Language\Language;
use Temple\Languages\Core\Nodes\BlockNode;
use Temple\Languages\Core\Nodes\ExtendNode;
use Temple\Languages\Core\Nodes\UseNode;
use Temple\Languages\Core\Nodes\VariableNode;
use Temple\Languages\Core\Plugins\ExtendPlugin;
use Temple\Languages\Core\Plugins\VariablesPlugin;


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