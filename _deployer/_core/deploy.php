<?php


require_once __DIR__ . "/../vendor/autoload.php";



$parsedown = new Parsedown();
$yaml      = new \Symfony\Component\Yaml\Yaml();

$loader = new \Twig_Autoloader();
$loader->register(__DIR__ . "/../templates");
$loader = new \Twig_Loader_Filesystem(__DIR__ . "/../templates");
if (!is_dir(__DIR__ . "/../cache")) {
    mkdir(__DIR__ . "/../cache", 0777, true);
}
$twig = new \Twig_Environment($loader, array('cache' => __DIR__ . "/../cache",));
$twig->clearCacheFiles();

$tree = new \Deployer\MarkDownTree($twig);
$tree = $tree->getTree();

new \Deployer\ParseMarkdown($tree, __DIR__, $parsedown, $twig);
new \Deployer\ParseTwig(__DIR__, $yaml, $parsedown, $twig);
new \Deployer\ParseAssets(__DIR__);

echo "... deployed assets & page\n";