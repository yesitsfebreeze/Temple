<?php

namespace Docs;


class parseTwig
{
    public function __construct($dir)
    {
        $dir = $dir . '/../templates';
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem($dir);
        $twig   = new \Twig_Environment($loader, array('cache' => $dir . "/cache",));
        $full   = $twig->render('index.twig');
        file_put_contents($dir . "/../index.html", $full);
    }
}