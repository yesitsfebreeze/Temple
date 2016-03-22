<?php


require_once __DIR__ . "/../vendor/autoload.php";

$tree = new \Deploy\MarkDownTree();
$tree = $tree->getTree();

$parsedown = new Parsedown();
$yaml      = new \Symfony\Component\Yaml\Yaml();
$less      = new lessc();

new \Deploy\ParseMarkdown($tree, __DIR__, $parsedown);
new \Deploy\parseTwig(__DIR__, $yaml, $parsedown);
new \Deploy\parseAssets(__DIR__, $less);

echo "... deployed assets & page\n";