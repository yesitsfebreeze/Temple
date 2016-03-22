<?php

namespace Deploy;


class parseAssets
{
    public function __construct($dir, \lessc $less)
    {
        $assets = $dir . "/../assets/";
        $less->checkedCompile($assets . "dev/less/main.less", $assets . "prod/style.css");
    }
}