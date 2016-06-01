<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Utilities\Config;

class Template extends DependencyInstance
{

    /** @var  Lexer $Lexer */
    protected $Lexer;

    /** @var  Parser $Parser */
    protected $Parser;

    /** @var  Config $Config */
    protected $Config;

    /** @var  Cache $Cache */
    protected $Cache;

    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config",
            "Template/Lexer" => "Lexer",
            "Template/Parser" => "Parser",
            "Template/Cache" => "Cache"
        );
    }


    public function addDirectory($dir)
    {
        # Directory service -> add
        # the directory service will add them into the config
        return $dir;
    }

    public function removeDirectory()
    {
        # Directory service -> add
        # the directory service will add them into the config
    }

    public function getDirectories()
    {
        # Directory service -> add
        # the directory service will add them into the config
    }


    public function showTemplate($filename)
    {
        # renders and includes the template
    }

    public function fetchTemplate($filename)
    {
        # renders and includes the template
    }

}