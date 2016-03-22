<?php

namespace Deploy;


class parseAssets
{
    public function __construct($dir, \lessc $less)
    {
        $assets = $dir . "/../assets/";
        $less->checkedCompile($assets . "less/main.less", $assets . "prod/");
    }
}