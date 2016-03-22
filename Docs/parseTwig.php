<?php

namespace Docs;




use Symfony\Component\Yaml\Yaml;

class parseTwig
{
    public function __construct($dir, Yaml $yaml)
    {
        $config = $dir . "/../config.yml";
        $config = $yaml->parse(file_get_contents($config));
        var_dump($config);
        die();
        $dir = $dir . '/../templates';
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem($dir);
        $twig   = new \Twig_Environment($loader, array('cache' => $dir . "/cache",));
        $twig->clearCacheFiles();
        $full       = $twig->render('index.twig');
        $outputFile = $dir . "/../index.html";
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);
        file_put_contents($outputFile, $full);
    }
}