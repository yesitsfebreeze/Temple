<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;

class Template extends DependencyInstance
{

    /** @var  Lexer $Lexer */
    protected $Lexer;

    /** @var  Parser $Parser */
    protected $Parser;

    /** @var  Config $Config */
    protected $Config;

    /** @var  Directories $Directories */
    protected $Directories;

    /** @var  Cache $Cache */
    protected $Cache;


    public function dependencies()
    {
        return array(
            "Utilities/Directories" => "Directories",
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


    /**
     * renders and includes the template
     *
     * @param $filename
     * @throws TempleException
     */
    public function showTemplate($filename)
    {
        $file          = $this->getTemplateFile($filename);
        $templateFiles = $this->getTemplateFiles($file);
        $dom           = $this->Lexer->lex($templateFiles, $file);
        $cacheFile     = $this->Parser->parse($dom);

        if (file_exists($cacheFile)) {
            include $cacheFile;
        } else {
            throw new TempleException("Could not include template cache file!", $cacheFile);
        }
    }


    public function fetchTemplate($filename)
    {
        # renders and includes the template
    }


    public function getTemplateFile($templateFile)
    {
        $files = $this->Directories->get("template");

        # $files search for $templateFile
        # returns template path
        $file = "found file";

        return $file;
    }


    public function getTemplateFiles($templateFile)
    {
        $files = $this->Directories->get("template");
        # $files search for $templateFile
        # returns template path
        $file = "found file";

        return $file;
    }
}