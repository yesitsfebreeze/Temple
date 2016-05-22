<?php

namespace Caramel\Services;


use Caramel\Exception\CaramelException;
use Caramel\Models\DomModel;
use Caramel\Models\ServiceModel;

class TemplateService extends ServiceModel
{


    /**
     * Renders and includes the passed file
     *
     * @param $file
     * @throws CaramelException
     */
    public function show($file)
    {
        $templateFile = $this->parse($file);
        # scoped Caramel
        /** @noinspection PhpUnusedLocalVariableInspection */
        $_crml = $this;

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
        return $this->dirs->add($dir, "templates");
    }


    /**
     * removes a template directory
     *
     * @param integer $pos
     * @return string
     */
    public function remove($pos)
    {
        return $this->dirs->remove($pos, "templates");
    }


    /**
     * returns all template directories
     *
     * @return array
     */
    public function getDirs()
    {
        return $this->dirs->get("templates");
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
     * returns all found template files for the given abbreviation
     *
     * @param string $file
     * @return array
     * @throws CaramelException
     */
    public function findTemplates($file)
    {
        # get the file extension
        # add add the config extension if it doesn't exist
        $ext       = strrev(substr(strrev($file), 0, 4));
        $configExt = '.' . $this->config->get("template.extension");
        if ($ext != $configExt) $file = $file . $configExt;

        $files = array();
        foreach ($this->getDirs() as $level => $templateDir) {
            # concat all template directories
            # with he passed file path
            $template = $templateDir . $file;
            # add them to our array if they exist
            if (file_exists($template)) $files[ $level ] = $template;
        }
        # if we found some files return them
        if (sizeof($files) > 0) return $files;

        # otherwise throw an error
        throw new CaramelException("Can't find template file.", $file);
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
            /** @var DomModel $dom */
            $dom = $this->lexer->lex($file);
            $this->parser->parse($dom);
        }

        return $this->cache->getPath($file);
    }

}