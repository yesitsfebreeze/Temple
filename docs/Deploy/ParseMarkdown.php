<?php

namespace Deploy;


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

        $outputFile = $dir . "/../templates/generated/api.html";
        if (file_exists($outputFile)) {
            unlink($outputFile);
        } else {
            mkdir(dirname($outputFile), 0777, true);
        }
        touch($outputFile);
        file_put_contents($outputFile, $content);
    }
}