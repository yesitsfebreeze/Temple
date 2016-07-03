<?php

$dependencies = array(
    "Utilities/Directories"   => array(
        "Utilities/Config" => "Config"
    ),
    "Subscribers" => array(
        "EventManager/EventManager" => "EventManager"
    ),
    "Template/Compiler"         => array(
        "EventManager/EventManager" => "EventManager"
    ),
    "Template/Lexer"          => array(
        "Utilities/Config"      => "Config",
        "Utilities/Directories" => "Directories",
        "EventManager/EventManager" => "EventManager"
    ),
    "Template/Cache"          => array(
        "Utilities/Config"      => "Config",
        "Utilities/Directories" => "Directories"
    ),
    "Template/Template"       => array(
        "Utilities/Directories" => "Directories",
        "Utilities/Config"      => "Config",
        "Template/Lexer"        => "Lexer",
        "Template/Compiler"       => "Compiler",
        "Template/Cache"        => "Cache",
    )
);