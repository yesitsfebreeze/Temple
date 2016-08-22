<?php

namespace Temple\Languages\Core;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Structs\Language;
use Temple\Languages\Core\Nodes\BlockNode;
use Temple\Languages\Core\Nodes\ExtendNode;
use Temple\Languages\Core\Nodes\IncludeNode;
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
     * registers the the nodes for the language
     */
    public function register()
    {
        $this->EventManager = $this->Instance->EventManager();
        $this->registerNodes();
        $this->registerPlugins();
    }


    /**
     * registers the core nodes
     */
    private function registerNodes()
    {
        $this->subscribe("node.use", new LanguageNode());
        $this->subscribe("node.extend", new ExtendNode());
        $this->subscribe("node.block", new BlockNode());
        $this->subscribe("node.include", new IncludeNode());
    }


    /**
     * registers the core plugins
     */
    private function registerPlugins()
    {
        $this->subscribe("plugin.dom.extend", new ExtendPlugin());
    }
}