<?php

namespace Underware\Engine;


use Underware\Engine\Exception\Handler;
use Underware\Engine\Injection\Injection;


class Config extends Injection
{
    /** @var null */
    private $subfolder = null;

    /** @var bool */
    private $errorHandler = true;

    /** @var Handler */
    private $errorHandlerInstance;

    /** @var string */
    private $cacheDir = "./Cache";

    /** @var bool */
    private $cacheEnabled = true;

    /** @var array */
    private $templateDirs = array();

    /** @var string */
    private $IndentCharacter = " ";

    /** @var int */
    private $IndentAmount = 2;

    /** @var string */
    private $extension = "slip";

    /** @var bool */
    private $showComments = true;

    /** @var bool */
    private $showBlockComments = true;


    /**
     * updates the config
     */
    public function update()
    {
        if ($this->errorHandler) {
            $this->errorHandlerInstance = new Handler();
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

}

//
//<?php
//
//$config = array(
//    "parser" => [
//        "selfClosing" => [
//
//        ],
//        "inline" => [
//            "b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea"
//        ],
//    ]
//);
