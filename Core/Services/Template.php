<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;
use Caramel\Models\Dom;


/**
 * Class Template
 *
 * @package Caramel
 */
class Template extends Service
{

    /**
     * Renders and includes the passed file
     *
     * @param $file
     * @throws CaramelException
     */
    public function show($file)
    {
        $this->plugins->init();
        $templateFile = $this->parse($file);

        # add the file header if wanted
        $fileHeader = $this->config->get("file_header");
        if ($fileHeader) {
            echo "<!-- " . $fileHeader . " -->";
        }

        # scoped Caramel
        /** @noinspection PhpUnusedLocalVariableInspection */
        $_crml = $this->caramel;

        if (file_exists($templateFile)) {
            /** @noinspection PhpIncludeInspection */
            include $templateFile;
        } else {
            throw new CaramelException("Can't include $file.crml");
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
     * Renders and returns the passed dom
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