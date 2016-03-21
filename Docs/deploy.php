<?php

require_once "../vendor/autoload.php";

$tree = new \Docs\MarkDownTree();
$tree = $tree->getTree();

$parseDown = new Parsedown();

$content = "";
foreach ($tree as $item) {
    $content .= $parseDown->parse(file_get_contents($item["file"]));
}

$outputFile = __DIR__ . "/../output/api.html";
if (is_file($outputFile)) {
    unlink($outputFile);
}
touch($outputFile);
file_put_contents($outputFile, $content);
