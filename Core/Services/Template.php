<?php

namespace Caramel\Services;


use Caramel\Caramel;
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
        } catch (\Exception $e) {
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