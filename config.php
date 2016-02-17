<?php

$config = array(

    # disable the cache
    "nocache" => true,

    # what type of generator should be used by default
    "default_template_state" => "dynamic",

    # our template file extension
    "extension" => "crml",

    # what characters should be used to indent the output files
    "output_indent" => "    ",

    # directory where we want the cache to be located at
    "cache_dir" => "cache",

    # display comment in the frontend
    "show_comments" => true,

    # display comment in the frontend
    "show_blocks" => false,

    # default self closing items
    "self_closing" => array("area", "base", "br", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr"),

    # default self closing items
    // TODO: get this from a resource
    "inline_elements" => array("p", "a", "span", "label"),

    # the file header which gets added to all templates
    # if you don't want one, set it to false
    "file_header" => "Caramel Example Template File &copy; hvlmnns"
);