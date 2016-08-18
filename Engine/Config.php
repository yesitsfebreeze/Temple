<?php

namespace Temple\Engine;


use Temple\Engine\Exception\ExceptionHandler;
use Temple\Engine\InjectionManager\Injection;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends Injection
{

    /** @var bool $shutdownCallbackRegistered */
    private $shutdownCallbackRegistered = false;

    /** @var  InstanceWrapper $InstanceWrapper */
    private $InstanceWrapper;

    /** @var null $subfolder */
    private $subfolder = null;

    /** @var bool $errorHandler */
    private $errorHandler = true;

    /** @var ExceptionHandler $errorHandlerInstance */
    private $errorHandlerInstance;

    /** @var string $cacheDir */
    private $cacheDir = "./Cache";

    /** @var bool $cacheEnabled */
    private $cacheEnabled = false;

    /** @var bool $CacheInvalidation */
    private $CacheInvalidation = true;

    /** @var bool $variableCacheEnabled */
    private $variableCacheEnabled = true;

    /** @var array $templateDirs */
    private $templateDirs = array();

    /** @var string $IndentCharacter tab or space */
    private $IndentCharacter = "space";

    /** @var int $IndentAmount */
    private $IndentAmount = 4;

    /** @var string $extension */
    private $extension = "tmpl";

    /** @var string $variablePattern */
    private $variablePattern = "{{%}}";

    /** @var bool $showComments */
    private $showComments = true;

    /** @var bool $showBlockComments */
    private $showBlockComments = true;

    /** @var array $languages */
    private $defaultLanguages = array("html");

    /** @var bool $useCoreLanguage */
    private $useCoreLanguage = true;

    /** @var array $processedTemplates */
    private $processedTemplates = array();


    /**
     * @param InstanceWrapper $InstanceWrapper
     */
    public function setInstanceWrapper($InstanceWrapper)
    {
        $this->InstanceWrapper = $InstanceWrapper;
    }


    /**
     * updates the config
     */
    public function update()
    {

        if (!$this->shutdownCallbackRegistered) {
            register_shutdown_function(function (Config $configInstance) {
                $config = array(
                    "cacheDir"           => $configInstance->InstanceWrapper->DirectoryHandler()->getCacheDir(),
                    "subfolder"          => $configInstance->getSubfolder(),
                    "cacheEnabled"       => $configInstance->isCacheEnabled(),
                    "templateDirs"       => $configInstance->getTemplateDirs(),
                    "processedTemplates" => $configInstance->getProcessedTemplates(),
                    "IndentCharacter"    => $configInstance->getIndentCharacter(),
                    "IndentAmount"       => $configInstance->getIndentAmount(),
                    "extension"          => $configInstance->getExtension(),
                    "defaultLanguages"   => $configInstance->getDefaultLanguages(),
                    "useCoreLanguage"    => $configInstance->isUseCoreLanguage(),
                    "DocumentRoot"       => $_SERVER["DOCUMENT_ROOT"]
                );
                $key    = md5(serialize($configInstance));
                $configInstance->InstanceWrapper->ConfigCache()->save($key, $config);
            }, $this);
            $this->shutdownCallbackRegistered = true;
        }


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
    public function isCacheInvalidation()
    {
        return $this->CacheInvalidation;
    }


    /**
     * @param boolean $CacheInvalidation
     */
    public function setCacheInvalidation($CacheInvalidation)
    {
        $this->CacheInvalidation = $CacheInvalidation;
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
     * @return string
     */
    public function getVariablePattern()
    {
        return $this->variablePattern;
    }


    /**
     * @param string $variablePattern
     */
    public function setVariablePattern($variablePattern)
    {
        $this->variablePattern = $variablePattern;
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
    public function isUseCoreLanguage()
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


    /**
     * @return array
     */
    public function getProcessedTemplates()
    {
        return $this->processedTemplates;
    }


    /**
     * @param array $processedTemplate
     */
    public function addProcessedTemplate($processedTemplate)
    {
        $this->processedTemplates[] = $processedTemplate;
    }


}