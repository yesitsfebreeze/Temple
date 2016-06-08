<?php

namespace Temple\Plugin;


use Temple\Models\Plugin\Plugin;


class outputprocessor extends Plugin
{

    public function position()
    {
        return 2;
    }


    /**
     * @return bool
     */
    public function isOutputProcessor()
    {
        return true;
    }


    public function process($element)
    {
//        var_dump("outputprocessor",$element);
        return $element;
    }

}