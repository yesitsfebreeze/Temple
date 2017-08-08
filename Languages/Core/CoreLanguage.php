<?php

namespace Temple\Languages\Core;


use Temple\Engine\Languages\Language;
use Temple\Languages\Core\Nodes\BlockNode;
use Temple\Languages\Core\Nodes\ExtendNode;
use Temple\Languages\Core\Nodes\IncludeNode;
use Temple\Languages\Core\Nodes\LanguageNode;
use Temple\Languages\Core\Plugins\ExtendPlugin;


/**
 * Class Language
 *
 * @package Temple\Languages\Core
 */
class CoreLanguage extends Language
{

    /**
     * registers the the nodes for the language
     */
    public function register()
    {
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