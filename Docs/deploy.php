<?php


require_once "../vendor/autoload.php";

$tree = new \Docs\MarkDownTree();
$tree = $tree->getTree();

$parsedown = new Parsedown();
$yaml      = new \Symfony\Component\Yaml\Yaml();
$sass      = new SassCompiler();

new \Docs\ParseMarkdown($tree, __DIR__, $parsedown);
new \Docs\parseTwig(__DIR__, $yaml, $parsedown);
new \Docs\parseAssets(__DIR__, $sass);
