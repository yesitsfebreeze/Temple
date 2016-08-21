<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\Cache;
use Temple\Engine\Filesystem\CacheInvalidator;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\Filesystem\VariableCache;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Page;


/**
 * Class ExceptionTemplate
 *
 * @package Temple\Engine
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

    /** @var  Lexer $Lexer */
    protected $Lexer;

    /** @var  Compiler $Compiler */
    protected $Compiler;

    /** @var  Languages $Languages */
    protected $Languages;

    /** @var  EventManager $EventManager */
    protected $EventManager;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/Filesystem/Cache"            => "Cache",
            "Engine/Filesystem/CacheInvalidator" => "CacheInvalidator",
            "Engine/Lexer"                       => "Lexer",
            "Engine/Compiler"                    => "Compiler",
            "Engine/Languages"                   => "Languages",
            "Engine/EventManager/EventManager"   => "EventManager"
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
        $this->CacheInvalidator->checkValidation();
        $cacheFile = $this->process($file);
        $page      = new Page();
        $page->setFileName($file);
        $page->setFile($cacheFile);

        $event = $this->EventManager->notify("template.show.page", array("Page" => $page, "Template" => $this));
        /** @var Page $page */
        $page = $event["Page"];
//        $this->VariableCache->setFile($file);
//        $page->setVariables($this->VariableCache->getMergedVariables());


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
    public function process($file, $level = 0)
    {
        $file = $this->cleanExtension($file);

        $extension = $this->getFileExtension($file);

        if ($this->Cache->isModified($file)) {
            $dom     = $this->dom($file, $level);
            $content = $this->fetch($file, $level, $dom);
            $this->Cache->save($file, $content, $dom, $extension);
        }

        $cacheFile = $this->Cache->getFile($file, $extension);

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
     * @param DOM    $Dom
     *
     * @return string
     */
    public function fetch($file, $level = 0, $Dom = null)
    {
        if (is_null($Dom)) {
            $Dom = $this->dom($file, $level);
        }
        $content = $this->Compiler->compile($Dom);
        $this->Config->addProcessedTemplate($file);

        $this->EventManager->notify("template.fetch", $this);
//        $this->VariableCache->setFile($file);
//        $this->VariableCache->setDom($Dom);
//        $this->VariableCache->saveTemplateVariables();


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
        return $this->DirectoryHandler->templateExists($file);
    }


    /**
     * @param $file
     *
     * @return null|string|void
     */
    public function getFileExtension($file)
    {
        $extension = null;
        $template  = $this->templateExists($file);
        $lang      = $this->Languages->getLanguageFromFile($template);
        $extension = $lang->getExtension();

        return $extension;
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
        return $this->DirectoryHandler->cleanExtension($file);
    }

}