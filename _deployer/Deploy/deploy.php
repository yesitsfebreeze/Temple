<?php


require_once __DIR__ . "/../vendor/autoload.php";

$tree = new \Deploy\MarkDownTree();
$tree = $tree->getTree();

$parsedown = new Parsedown();
$yaml      = new \Symfony\Component\Yaml\Yaml();

new \Deploy\ParseMarkdown($tree, __DIR__, $parsedown);
new \Deploy\ParseTwig(__DIR__, $yaml, $parsedown);
new \Deploy\ParseAssets(__DIR__);

echo "... deployed assets & page\n";