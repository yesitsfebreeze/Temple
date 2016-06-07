<?php

$config = array(
    "subfolder" => null,
    "errorHandler" => true,
    "dirs" => [
        "cache" => "./Cache",
        "template" => [],
        "plugins" => [],
    ],
    "cache" => [
        "enable" => true
    ],
    "template" => [
        "indent" => [
            "character" => " ",
            "amount" => 2
        ],
        "extension" => "tmpl",
        "symbols" => [
            "comment" => "#",
            "variable" => "$"
        ],
        "tag" => [
            "opening" => [
                "before" => "<",
                "after" => ">"
            ],
            "closing" => [
                "before" => "</",
                "after" => ">"
            ]
        ],
        "delimiter" => [
            "left" => "{",
            "right" => "}"
        ],
        "comments" => [
            "show" => false,
            "blocks" => false
        ]
    ],
    "parser" => [
        "self closing" => [
            "br", "area", "base", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr"
        ],
        "inline" => [
            "b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea"
        ],
    ]
);
