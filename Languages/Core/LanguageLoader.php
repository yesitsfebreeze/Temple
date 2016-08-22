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
     * subscribe the nodes for the language
     */
    public function register()
    {
        $this->EventManager = $this->Instance->EventManager();
        $this->registerNodes();
        $this->registerPlugins();
    }


    /**
     * subscribe core nodes
     */
    private function registerNodes()
    {
        $this->subscribe("node.use", new LanguageNode());
        $this->subscribe("node.extend", new ExtendNode());
        $this->subscribe("node.block", new BlockNode());
        $this->subscribe("node.include", new IncludeNode());
    }


    /**
     * subscribe core plugins
     */
    private function registerPlugins()
    {
        $this->subscribe("plugin.dom.extend", new ExtendPlugin());
    }
}