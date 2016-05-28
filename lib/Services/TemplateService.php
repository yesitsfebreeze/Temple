<?php

namespace Temple\Services;


use Temple\Exception\TempleException;
use Temple\Models\DomModel;
use Temple\Models\ServiceModel;

class TemplateService extends ServiceModel
{


    /**
     * Renders and includes the passed file
     *
     * @param $file
     * @throws TempleException
     */
    public function show($file)
    {
        $templateFile = $this->parse($file);
        # scoped Temple
        /** @noinspection PhpUnusedLocalVariableInspection */
        $_crml = $this;

        if (file_exists($templateFile)) {
            /** @noinspection PhpIncludeInspection */
            include $templateFile;
        } else {
            throw new TempleException("Can't include $file.crml");
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
        return $this->directoryService->add($dir, "templates");
    }


    /**
     * removes a template directory
     *
     * @param integer $pos
     * @return string
     */
    public function remove($pos)
    {
        return $this->directoryService->remove($pos, "templates");
    }


    /**
     * returns all template directories
     *
     * @return array
     */
    public function getDirs()
    {
        return $this->directoryService->get("templates");
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
     * @throws TempleException
     */
    public function findTemplates($file)
    {
        # get the file extension
        # add add the config extension if it doesn't exist
        $ext       = strrev(substr(strrev($file), 0, 4));
        $configExt = '.' . $this->configService->get("template.extension");
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
        throw new TempleException("Can't find template file.", $file);
    }


    /**
     * parsed a template file
     *
     * @param $file
     * @return mixed|string
     */
    public function parse($file)
    {
        if ($this->cacheService->modified($file)) {
            /** @var DomModel $dom */
            $dom = $this->lexerService->lex($file);
            $this->parserService->parse($dom);
        }

        return $this->cacheService->getPath($file);
    }

}