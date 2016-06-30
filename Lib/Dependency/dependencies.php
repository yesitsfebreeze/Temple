<?php

$dependencies = array(
    "Utilities/Directories"  => array(
        "Utilities/Config" => "Config"
    ),
    "Template/NodeFactory"   => array(
        "Utilities/Config" => "Config"
    ),
    "Template/PluginFactory" => array(
        "Utilities/Directories" => "Directories"
    ),
    "Template/Plugins"       => array(
        "Utilities/Config"       => "Config",
        "Utilities/Directories"  => "Directories",
        "Template/PluginFactory" => "PluginFactory"
    ),
    "Template/Parser"        => array(
        "Template/Plugins" => "Plugins"
    ),
    "Template/Lexer"         => array(
        "Utilities/Config"      => "Config",
        "Utilities/Directories" => "Directories",
        "Template/NodeFactory"  => "NodeFactory",
        "Template/Plugins"      => "Plugins"
    ),
    "Template/Cache"         => array(
        "Utilities/Config"      => "Config",
        "Utilities/Directories" => "Directories"
    ),
    "Template/Template"      => array(
        "Utilities/Directories" => "Directories",
        "Utilities/Config"      => "Config",
        "Template/Lexer"        => "Lexer",
        "Template/Parser"       => "Parser",
        "Template/Cache"        => "Cache",
        "Template/Plugins"      => "Plugins"
    )
);