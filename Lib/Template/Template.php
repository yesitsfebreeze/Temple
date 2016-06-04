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
            "Utilities/Config"      => "Config",
            "Template/Lexer"        => "Lexer",
            "Template/Parser"       => "Parser",
            "Template/Cache"        => "Cache"
        );
    }


    /**
     * adds a directory to the templates
     *
     * @param $dir
     * @return mixed
     */
    public function addDirectory($dir)
    {
        $this->Directories->add($dir, "template");

        return $dir;
    }


    /**
     * removes the template directory for the passed level
     *
     * @param int $level
     * @return bool
     */
    public function removeDirectory($level = 0)
    {
        return $this->Directories->remove($level, "template");
    }


    /**
     * returns the template directories
     *
     * @return mixed
     */
    public function getDirectories()
    {
        return $this->Directories->get("template");
    }


    /**
     * renders and includes the template
     *
     * @param $file
     * @throws TempleException
     */
    public function show($file)
    {

        $cacheFile = $this->fetch($file);

        /** @noinspection PhpIncludeInspection */
        include $cacheFile;

    }


    /**
     * processes and saves the file to the cache, then return the cache file path
     *
     * @param string $file
     * @param int    $level
     * @return string
     */
    public function fetch($file, $level = 0)
    {

        $parsedContent = $this->process($file, $level);
        $cacheFile     = $this->Cache->save($file, $parsedContent, $level);

        return $cacheFile;

    }


    /**
     * processes and returns the parsed content
     *
     * @param string $file
     * @param int    $level
     * @return string
     */
    public function process($file, $level = 0)
    {

        $file          = $this->cleanExtension($file);
        $dom           = $this->Lexer->lex($file, $level);
        $parsedContent = $this->Parser->parse($dom);

        return $parsedContent;

    }


    private function cleanExtension($file)
    {
        $file = strrev(preg_replace("/.*?\./", "", strrev($file))) . "." . $this->Config->get("template.extension");

        return $file;
    }

}