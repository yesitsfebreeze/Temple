<?php

namespace Temple\Languages\Js;


use Temple\Engine\Languages\BaseLanguage;
use Temple\Languages\Js\Nodes\IncludeNode;
use Temple\Languages\Js\Nodes\TestNode;


/**
 * this is the default language
 * it renders to a mix of html and php
 * Class Language
 *
 * @package Temple\Languages\Js
 */
class Language extends BaseLanguage
{

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