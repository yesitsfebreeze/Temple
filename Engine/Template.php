<?php

namespace Temple\Engine;


use Temple\Engine\Cache\TemplateCache;
use Temple\Engine\Cache\VariablesBaseCache;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Languages\BaseLanguage;
use Temple\Engine\Languages\LanguageConfig;
use Temple\Engine\Languages\Languages;
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

    /** @var  TemplateCache TemplateCache */
    protected $TemplateCache;

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
            "Engine/Config" => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/Cache/TemplateCache" => "TemplateCache",
            "Engine/Lexer" => "Lexer",
            "Engine/Compiler" => "Compiler",
            "Engine/Languages/Languages" => "Languages",
            "Engine/EventManager/EventManager" => "EventManager"
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
        $cacheFile = $this->process($file);
        $page = new Page();
        $page->setFileName($file);
        $page->setFile($cacheFile);
        $template = $this->getTemplatePath($file);
        /** @var BaseLanguage $lang */
        $lang = $this->Languages->getLanguageFromFile($template);
        $VariableCache = $lang->getConfig()->getVariableCache();
        if ($VariableCache instanceof VariablesBaseCache) {
            $VariableCache->setFile($file);
            $page->setVariables($VariableCache->getVariables());
        }

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
        $file = $this->normalizeExtension($file);

        $folder = $this->Languages->getLanguageFromFile($file)->getConfig()->getName();

        if ($this->TemplateCache->changed($file)) {
            $dom = $this->dom($file, $level);
            $content = $this->fetch($file, $level, $dom, true);

            return $this->TemplateCache->dump($file, $content);
        } else {
            return $this->TemplateCache->get($file, $folder);
        }
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
        $file = $this->normalizeExtension($file);
        $dom = $this->Lexer->lex($file, $level);

        return $dom;
    }


    /**
     * processes and returns the parsed content
     *
     * @param string $file
     * @param int    $level
     * @param DOM    $Dom
     * @param bool   $changed
     *
     * @return string
     */
    public function fetch($file, $level = 0, $Dom = null, $changed = false)
    {
        if (!$changed) {
            $changed = $this->TemplateCache->changed($file);
        }

        if ($changed) {
            if (is_null($Dom)) {
                $Dom = $this->dom($file, $level);
            }
            $content = $this->Compiler->compile($Dom);
            $this->Config->addProcessedTemplate($file);

            /** @var LanguageConfig $language */
            $language = $Dom->getLanguage()->getConfig();
            $languageName = $language->getName();
            $this->EventManager->dispatch($languageName, "template.fetch", $this);

            $template = $this->getTemplatePath($file);
            /** @var BaseLanguage $lang */
            $lang = $this->Languages->getLanguageFromFile($template);
            $VariableCache = $lang->getConfig()->getVariableCache();
            if ($VariableCache instanceof VariablesBaseCache) {
                $VariableCache->setFile($file);
                $VariableCache->saveVariables($Dom->getVariables());
            }
        } else {
            $cacheFile = $this->TemplateCache->get($file);
            $content = file_get_contents($cacheFile);
        }

        return $content;

    }


    /**
     * checks if a template file exists within the template directories
     *
     * @param $file
     * @param $exception bool
     *
     * @return bool
     */
    public function templateExists($file, $exception = false)
    {
        return $this->DirectoryHandler->templateExists($file, $exception);
    }


    /**
     * checks if a template file exists within the template directories
     *
     * @param $file
     *
     * @return bool
     */
    public function getTemplatePath($file)
    {
        return $this->DirectoryHandler->getTemplatePath($file);
    }


    /**
     * @param $file
     *
     * @return null|string|void
     * @throws Exception
     */
    public function getFileExtension($file)
    {
        $extension = null;
        $template = $this->templateExists($file, true);

        /** @var BaseLanguage $lang */
        $lang = $this->Languages->getLanguageFromFile($template);
        $extension = $lang->getConfig()->getExtension();

        return $extension;
    }


    /**
     * make sure we have the template extension
     *
     * @param $file
     *
     * @return string
     */
    private function normalizeExtension($file)
    {
        return $this->DirectoryHandler->normalizeExtension($file);
    }

}