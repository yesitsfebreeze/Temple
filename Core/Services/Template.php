<?php

namespace Caramel\Services;


use Caramel\Caramel;
use Caramel\Models\Dom;


/**
 * Class Template
 *
 * @package Caramel
 */
class Template
{


    /** @var Caramel $caramel */
    private $caramel;


    /**
     * @param Caramel $caramel
     */
    public function setCaramel(Caramel $caramel)
    {
        $this->caramel = $caramel;
    }


    /** @var Config $config */
    private $config;


    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }


    /** @var Directories $directories */
    private $directories;


    /**
     * @param Directories $directories
     */
    public function setDirectories(Directories $directories)
    {
        $this->directories = $directories;
    }


    /** @var Cache $cache */
    private $cache;


    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }


    /** @var Lexer $lexer */
    private $lexer;


    /**
     * @param Lexer $lexer
     */
    public function setLexer(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }


    /** @var Parser $parser */
    private $parser;


    /**
     * @param Parser $parser
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
    }
    

    /**
     * Renders and includes the passed file
     *
     * @param $file
     */
    public function show($file)
    {
        try {
            $templateFile = $this->parse($file);

            # add the file header if wanted
            $fileHeader = $this->config->get("file_header");
            if ($fileHeader) {
                echo "<!-- " . $fileHeader . " -->";
            }

            # scoped Caramel
            $_crml = $this->caramel;

            if (file_exists($templateFile)) {
                include $templateFile;
            } else {
                new Error("Can't include $file.crml");
            }
        } catch(\Exception $e) {
            new Error($e->getMessage());
        }
    }


    /**
     * adds a template directory
     *
     * @param $dir
     * @return string
     */
    public function add($dir)
    {
        return $this->directories->add($dir, "templates.dirs");
    }


    /**
     * removes a template directory
     *
     * @param integer $pos
     * @return string
     */
    public function remove($pos)
    {
        return $this->directories->remove($pos, "templates.dirs");
    }


    /**
     * returns all template directories
     *
     * @return array
     */
    public function dirs()
    {
        return $this->directories->get("templates.dirs");
    }


    /**
     * Renders and returns the passed file
     *
     * @param $file
     * @return string
     */
    public function fetch($file)
    {
        $templateFile      = $this->parse($file);
        $return            = array();
        $return["file"]    = $templateFile;
        $return["content"] = file_get_contents($templateFile);

        return $return;
    }


    /**
     * parsed a template file
     *
     * @param $file
     * @return mixed|string
     */
    public function parse($file)
    {
        if ($this->cache->modified($file)) {
            /** @var Dom $dom */
            $dom = $this->lexer->lex($file);
            $this->parser->parse($dom);
        }

        return $this->cache->getPath($file);
    }
}