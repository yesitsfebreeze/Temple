<?php

$config = array(

    # our template file extension
    "extension" => "crml",

    # disable the cache
    "use_cache" => false,

    # directory where we want the cache to be located at
    "cache_dir" => "../Cache",

    # the symbol used for identifying comments
    "comment_symbol" => "#",

    # display comments in the frontend
    "show_comments" => true,

    # display block name comments in the frontend
    "show_block_as_comments" => true,

    # default self closing items
    "self_closing" => array("import", "extend", "area", "base", "br", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr"),

    # default self closing items
    // TODO: get this from a resource
    "inline_elements" => array("p", "a", "span", "label"),

    # the file header which gets added to all templates
    # if you don't want one, set it to false
    "file_header" => "Caramel template engine."
);