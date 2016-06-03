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
     * @param $file
     * @param int $level
     * @return string
     */
    public function fetch($file, $level = 0)
    {

        $parsedContent = $this->process($file, $level);
        $cacheFile = $this->Cache->save($file, $parsedContent, $level);

        return $cacheFile;

    }

    /**
     * processes and returns the passed template file
     *
     * @param $file
     * @param int $level
     * @return string
     */
    public function process($file, $level = 0)
    {

        $file = $this->getTemplateFile($file, $level);
        $dom = $this->Lexer->lex($file);
        $parsedContent = $this->Parser->parse($dom);

        return $parsedContent;

    }


    /**
     * returns the template file for the passed level
     *
     * @param $file
     * @param int $level
     * @return string
     */
    public function getTemplateFile($file, $level = 0)
    {

        # has to return the first found template file within the hierarchy
        $file = "test";

        return $file;

    }

}