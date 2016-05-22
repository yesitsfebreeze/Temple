<?php

$config = array(
    "errorhandler" => true,
    "dirs" => [
        "cache" => "./Cache",
        "templates" => [],
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
        "extension" => "crml",
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
    "plugins" => [
        "containers" => [
            "global",
            "core",
            "html"
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
