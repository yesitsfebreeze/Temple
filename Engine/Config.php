<?php

namespace Temple\Engine;


use Temple\Engine\Exception\ExceptionHandler;
use Temple\Engine\InjectionManager\Injection;


/**
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends Injection
{
    /** @var null */
    private $subfolder = null;

    /** @var bool */
    private $errorHandler = true;

    /** @var ExceptionHandler */
    private $errorHandlerInstance;

    /** @var string */
    private $cacheDir = "./Cache";

    /** @var bool */
    private $cacheEnabled = false;

    /** @var bool */
    private $variableCacheEnabled = true;

    /** @var array */
    private $templateDirs = array();

    /**
     * tab or space
     *
     * @var string
     */
    private $IndentCharacter = "space";

    /** @var int */
    private $IndentAmount = 4;

    /** @var string */
    private $extension = "tmpl";

    /** @var bool */
    private $showComments = true;

    /** @var bool */
    private $showBlockComments = true;

    /** @var array $languages */
    private $defaultLanguages = array("html");

    /** @var bool $useCoreLanguage */
    private $useCoreLanguage = true;


    /**
     * updates the config
     */
    public function update()
    {
        if ($this->errorHandler) {
            $this->errorHandlerInstance = new ExceptionHandler();
        } else {
            $this->errorHandlerInstance = null;
        }
    }


    /**
     * @return null
     */
    public function getSubfolder()
    {
        return $this->subfolder;
    }


    /**
     * @param $subfolder
     *
     * @return null
     */
    public function setSubfolder($subfolder)
    {
        $this->subfolder = $subfolder;
        $this->update();

        return $this->subfolder;
    }


    /**
     * @return boolean
     */
    public function isErrorHandler()
    {
        return $this->errorHandler;
    }


    /**
     * @param $errorHandler
     *
     * @return bool
     */
    public function setErrorHandler($errorHandler)
    {
        $this->errorHandler = $errorHandler;
        $this->update();

        return $this->errorHandler;
    }


    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }


    /**
     * @param $cacheDir
     *
     * @return string
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
        $this->update();

        return $this->cacheDir;
    }


    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }


    /**
     * @param $cacheEnabled
     *
     * @return bool
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;
        $this->update();

        return $this->cacheEnabled;
    }


    /**
     * @return boolean
     */
    public function isVariableCacheEnabled()
    {
        return $this->variableCacheEnabled;
    }


    /**
     * @param boolean $variableCacheEnabled
     */
    public function setVariableCacheEnabled($variableCacheEnabled)
    {
        $this->variableCacheEnabled = $variableCacheEnabled;
    }


    /**
     * @return array
     */
    public function getTemplateDirs()
    {
        return $this->templateDirs;
    }


    /**
     * @param $templateDirs
     *
     * @return array
     */
    public function setTemplateDirs($templateDirs)
    {
        $this->templateDirs = $templateDirs;
        $this->update();

        return $this->templateDirs;
    }


    /**
     * @return string
     */
    public function getIndentCharacter()
    {
        return $this->IndentCharacter;
    }


    /**
     * @param $IndentCharacter
     *
     * @return string
     */
    public function setIndentCharacter($IndentCharacter)
    {
        $this->IndentCharacter = $IndentCharacter;
        $this->update();

        return $this->IndentCharacter;
    }


    /**
     * @return int
     */
    public function getIndentAmount()
    {
        return $this->IndentAmount;
    }


    /**
     * @param $IndentAmount
     *
     * @return int
     */
    public function setIndentAmount($IndentAmount)
    {
        $this->IndentAmount = $IndentAmount;
        $this->update();

        return $this->IndentAmount;
    }


    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }


    /**
     * @param $extension
     *
     * @return bool
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        $this->update();

        return $this->extension;
    }


    /**
     * @return boolean
     */
    public function isShowComments()
    {
        return $this->showComments;
    }


    /**
     * @param $showComments
     *
     * @return bool
     */
    public function setShowComments($showComments)
    {
        $this->showComments = $showComments;
        $this->update();

        return $this->showComments;
    }


    /**
     * @return boolean
     */
    public function isShowBlockComments()
    {
        return $this->showBlockComments;
    }


    /**
     * @param $showBlockComments
     *
     * @return mixed
     */
    public function setShowBlockComments($showBlockComments)
    {
        $this->showBlockComments = $showBlockComments;
        $this->update();

        return $this->showBlockComments;
    }


    /**
     * @return array
     */
    public function getDefaultLanguages()
    {
        return $this->defaultLanguages;
    }


    /**
     * @param array $language
     */
    public function addDefaultLanguage($language)
    {
        if (!in_array($language, $this->defaultLanguages)) {
            $this->defaultLanguages[] = $language;
        }
    }


    /**
     * @param array $language
     */
    public function removeDefaultLanguage($language)
    {
        if (($key = array_search($language, $this->defaultLanguages)) !== false) {
            unset($this->defaultLanguages[ $key ]);
        }
    }


    /**
     * @return bool
     */
    public function getUseCoreLanguage()
    {
        return $this->useCoreLanguage;
    }


    /**
     * @param bool $useCoreLanguage
     */
    public function setUseCoreLanguage($useCoreLanguage)
    {
        $this->useCoreLanguage = $useCoreLanguage;
    }


}