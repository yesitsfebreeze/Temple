<?php

namespace Temple\Languages\Js;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Structs\Language;
use Temple\Languages\Js\Nodes\IncludeNode;
use Temple\Languages\Js\Nodes\TestNode;


/**
 * this is the default language
 * it renders to a mix of html and php
 * Class LanguageLoader
 *
 * @package Temple\Languages\Js
 */
class LanguageLoader extends Language
{

    /**
     * @return string
     */
    public function extension()
    {
        return "js";
    }


    /**
     * where the language saves the generated files
     *
     * @return string
     */
    public function cacheFolder()
    {
        return "/assets/js";
    }


    /**
     * subscribe the nodes for the language
     */
    public function register()
    {
        $this->registerNodes();
    }


    private function registerNodes()
    {
        $this->subscribe("node.include", new IncludeNode());
        $this->subscribe("node.test", new TestNode());
    }

}