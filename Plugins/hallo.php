<?php

namespace Temple\Plugin;


use Temple\Models\Nodes\BaseNode;
use Temple\Models\Plugins\Plugin;


class Hallo extends Plugin
{

    public function position()
    {
        return 2;
    }
    
    /**
     * @return bool
     */
    public function isPreProcessor(){
        return true;
    }


    public function process($element)
    {

        return $element;
    }

}