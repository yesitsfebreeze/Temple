<?php

namespace Docs;


class parseAssets
{
    public function __construct($dir, \SassCompiler $sass)
    {
        $assets = $dir . "/../assets/";
        $sass->run($assets . "sass/", $assets . "production/");
    }
}