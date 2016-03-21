<?php

require_once "../vendor/autoload.php";

$tree = new \Docs\MarkDownTree();
$tree = $tree->getTree();

$parseDown = new Parsedown();

new \Docs\ParseMarkdown($tree, __DIR__, $parseDown);
