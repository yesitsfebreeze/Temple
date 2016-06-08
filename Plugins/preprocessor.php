<?php

namespace Temple\Plugin;


use Temple\Models\Plugin\Plugin;


class preprocessor extends Plugin
{

    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isPreProcessor()
    {
        return true;
    }


    public function process($element)
    {
//        var_dump("preprocessor",$element);
        return $element;
    }

}