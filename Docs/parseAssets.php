<?php

namespace Docs;


class parseAssets
{
    public function __construct($dir, \scssc $scss)
    {
        $assets = $dir . "/../assets/";
        $scss->setImportPaths($assets . "sass");
        $style = $scss->compile('@import "main.scss"');
        file_put_contents($assets . "production/style.css", $style);

    }
}