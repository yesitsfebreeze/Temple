<?php

namespace Docs;


class ParseMarkdown
{
    public function __construct($tree, $dir, \Parsedown $parsedown)
    {
        $content = "";
        foreach ($tree as $item) {
            $markdown = $parsedown->parse(file_get_contents($item["file"]));
            $markdown = preg_replace("/%%level%%/", $item["level"], $markdown);
            $content .= $markdown;
        }

        $outputFile = $dir . "/../output/api.html";
        if (is_file($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);
        file_put_contents($outputFile, $content);
    }
}