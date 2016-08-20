<?php

namespace Temple\Languages\Core;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Structs\Language;
use Temple\Languages\Core\Nodes\BlockNode;
use Temple\Languages\Core\Nodes\ExtendNode;
use Temple\Languages\Core\Nodes\LanguageNode;
use Temple\Languages\Core\Plugins\ExtendPlugin;


/**
 * Class LanguageLoader
 *
 * @package Temple\Languages\Core
 */
class LanguageLoader extends Language
{

    /** @var  EventManager $EventManager */
    private $EventManager;


    /**
     * @return string
     */
    public function extension()
    {
        return "php";
    }


    /**
     * register the nodes for the language
     */
    public function register()
    {
        $this->EventManager = $this->Instance->EventManager();
        $this->registerNodes();
        $this->registerPlugins();
    }


    /**
     * register core nodes
     */
    private function registerNodes()
    {
        $this->EventManager->register("node.use", new LanguageNode());
        $this->EventManager->register("node.extend", new ExtendNode());
        $this->EventManager->register("node.block", new BlockNode());
    }


    /**
     * register core plugins
     */
    private function registerPlugins()
    {
        $this->EventManager->register("plugin.dom.extend", new ExtendPlugin());
    }
}