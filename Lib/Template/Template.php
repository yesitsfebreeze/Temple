<?php

namespace Pavel\Template;


use Pavel\DependencyManager\DependencyInstance;
use Pavel\Exception\Exception;
use Pavel\Models\Dom;
use Pavel\Utilities\Config;
use Pavel\Utilities\Directories;


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


    /** @inheritdoc */
    public function dependencies()
    {
        return $this->getDependencies();
    }


    /**
     * adds a directory to the templates
     *
     * @param $dir
     *
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
     *
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
     *
     * @throws Exception
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
     *
     * @return string
     */
    public function fetch($file, $level = 0)
    {

        $file = $this->cleanExtension($file);

        if ($this->Cache->isModified($file)) {
            $content = $this->process($file, $level);
            $this->Cache->save($file, $content, $level);
        }

        $cacheFile = $this->Cache->getFile($file);

        return $cacheFile;
    }


    /**
     * processes and returns the parsed content
     *
     * @param string $file
     * @param int    $level
     *
     * @return Dom
     */
    public function dom($file, $level = 0)
    {
        $file = $this->cleanExtension($file);
        $dom  = $this->Lexer->lex($file, $level);

        return $dom;
    }


    /**
     * processes and returns the parsed content
     *
     * @param string $file
     * @param int    $level
     *
     * @return string
     */
    public function process($file, $level = 0)
    {
        $dom     = $this->dom($file, $level);
        $content = $this->Parser->parse($dom);

        return $content;

    }


    /**
     * checks if a template file exists within the template directories
     *
     * @param $file
     *
     * @return bool
     */
    public function templateExists($file)
    {

        $dirs = $this->Directories->get("template");
        $file = $this->cleanExtension($file);
        foreach ($dirs as $level => $dir) {
            $checkFile = $dir . $file;
            if (file_exists($checkFile)) {
                return true;
            }
        }

        return false;
    }


    /**
     * make sure we have the template extension
     *
     * @param $file
     *
     * @return string
     */
    private function cleanExtension($file)
    {
        $file = preg_replace('/\..*?$/', '', $file) . "." . $this->Config->get("template.extension");

        return $file;
    }

}