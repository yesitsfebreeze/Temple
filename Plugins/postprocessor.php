<?php

namespace Temple\Plugin;


use Temple\Models\Plugin\Plugin;


class postprocessor extends Plugin
{

    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isPostProcessor()
    {
        return true;
    }


    public function process($element)
    {
//        var_dump("postprocessor",$element);
        return $element;
    }

}