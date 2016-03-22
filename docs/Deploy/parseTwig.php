<?php

namespace Deploy;


use Symfony\Component\Yaml\Yaml;

class parseTwig
{
    public function __construct($dir, Yaml $yaml, \Parsedown $parsedown)
    {
        $config = $dir . "/../config.yml";
        $config = $yaml->parse(file_get_contents($config));

        $includes = array();
        foreach ($config["includes"] as $include) {
            if (pathinfo($include, PATHINFO_EXTENSION) == "md") {
                $md         = $parsedown->parse(file_get_contents($dir . '/../pages/' . $include));
                $outputFile = $dir . '/../templates/generated/' . str_replace(".md", ".html", $include);
                $includes[] = str_replace($dir . '/../templates/', "", $outputFile);
                file_put_contents($outputFile, $md);
            }
        }
        $config["includes"] = $includes;

        $dir = $dir . '/../templates';
        \Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem($dir);
        $twig   = new \Twig_Environment($loader, array('cache' => $dir . "/cache",));
        $twig->clearCacheFiles();
        $full       = $twig->render('index.twig', $config);
        $outputFile = $dir . "/../../index.html";
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);
        file_put_contents($outputFile, $full);
    }
}