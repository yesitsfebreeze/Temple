<?php

$config = array(
    "use_cache" => false,
    "show_comments" => false,
    "block_comments" => false,
    "use_exception_handler" => true,
    "extension" => "crml",
    "cache_dir" => "../cache",
    "comment_symbol" => "#",
    "variable_symbol" => "$",
    "left_delimiter" => "{",
    "right_delimiter" => "}",
    "file_header" => "Caramel template engine.",
    "self_closing" => [
        "br", "area", "base", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr"
    ],
    "inline_elements" => [
        "b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea"
    ],
    "templates" => [
        "dirs" => []
    ],
    "plugins" => [
        "dirs" => []
    ],
    "plugin_containers" => [
        "global",
        "core",
        "html"
    ]
);
