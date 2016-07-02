<?php

$dependencies = array(
    "Utilities/Directories"   => array(
        "Utilities/Config" => "Config"
    ),
    "Events/Events" => array(
        "EventManager/EventManager" => "EventManager"
    ),
    "Template/Parser"         => array(
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
        "Template/Parser"       => "Parser",
        "Template/Cache"        => "Cache",
    )
);