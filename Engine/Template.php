<?php

namespace Underware\Engine;


use Underware\Engine\Filesystem\Cache;
use Underware\Engine\Filesystem\CacheInvalidator;
use Underware\Engine\Filesystem\DirectoryHandler;
use Underware\Engine\Filesystem\VariableCache;
use Underware\Engine\Injection\Injection;
use Underware\Engine\Structs\Dom;
use Underware\Engine\Structs\Page;
use Underware\Engine\Structs\Variables;


/**
 * Class ExceptionTemplate
 *
 * @package Underware\Engine
 */
class Template extends Injection
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var  Cache $Cache */
    protected $Cache;

    /** @var  CacheInvalidator $CacheInvalidator */
    protected $CacheInvalidator;

    /** @var  Variables $Variables */
    protected $Variables;

    /** @var  VariableCache $VariableCache */
    protected $VariableCache;

    /** @var  Lexer $Lexer */
    protected $Lexer;

    /** @var  Compiler $Compiler */
    protected $Compiler;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/Filesystem/Cache"            => "Cache",
            "Engine/Filesystem/CacheInvalidator" => "CacheInvalidator",
            "Engine/Structs/Variables"           => "Variables",
            "Engine/Filesystem/VariableCache"    => "VariableCache",
            "Engine/Lexer"                       => "Lexer",
            "Engine/Compiler"                    => "Compiler"
        );
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
        $this->DirectoryHandler->addTemplateDir($dir);

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
        return $this->DirectoryHandler->removeTemplateDir($level);
    }


    /**
     * returns the template directories
     *
     * @return mixed
     */
    public function getDirectories()
    {
        return $this->DirectoryHandler->getTemplateDirs();
    }


    /**
     * renders and includes the template
     *
     * @param string $file
     *
     * @return string
     */
    public function show($file)
    {
        $this->VariableCache->setFile($file);

        $this->CacheInvalidator->checkValidation();
        $cacheFile = $this->fetch($file);
        $page      = new Page();
        $page->setFileName($file);
        $page->setVariables($this->VariableCache->getMergedVariables());
        $page->setFile($cacheFile);

        return $page->display();
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
            $this->Cache->save($file, $content);
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
        $content = $this->Compiler->compile($dom);
        $this->VariableCache->setFile($file);
        $this->VariableCache->setDom($dom);
        $this->VariableCache->saveTemplateVariables();

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

        $dirs = $this->DirectoryHandler->getTemplateDirs();
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
        $file = preg_replace('/\..*?$/', '', $file) . "." . $this->Config->getExtension();

        return $file;
    }

}