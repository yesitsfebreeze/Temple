<?php

namespace Temple\Plugin;


use Temple\Models\Plugins\Plugin;


class Test extends Plugin
{

    public function position()
    {
        return 1;
    }


    public function isOutputProcessor()
    {
        return true;
    }
    

}