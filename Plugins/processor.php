<?php

namespace Temple\Plugin;


use Temple\Models\Plugin\Plugin;


class processor extends Plugin
{

    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isProcessor()
    {
        return true;
    }


    public function process($element)
    {
        
        return $element;
    }

}