<?php


require_once __DIR__ . "/../vendor/autoload.php";


$parsedown = new Parsedown();
$yaml      = new \Symfony\Component\Yaml\Yaml();

$loader = new \Twig_Autoloader();
$loader->register(__DIR__ . "/../templates");
$loader = new \Twig_Loader_Filesystem(__DIR__ . "/../templates");
$twig   = new \Twig_Environment($loader, array('cache' => false));
$twig->clearCacheFiles();

new \Deployer\ParseTwig(__DIR__, $yaml, $parsedown, $twig);
new \Deployer\ParseAssets(__DIR__);

echo "... deployed assets & page\n";