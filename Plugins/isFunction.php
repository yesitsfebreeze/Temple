<?php

namespace Temple\Plugin;


use Temple\Models\Plugin\Plugin;


class isFunction extends Plugin
{

    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isFunction()
    {
        return true;
    }


    public function process($element)
    {
//        var_dump("function");
        return $element;
    }

}