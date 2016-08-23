<?php

namespace Temple\Engine;


use Temple\Engine;
use Temple\Engine\Cache\VariablesBaseCache;
use Temple\Engine\Exception\Exception;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class LanguageConfig
{

    /** @var Engine $Engine */
    protected $Engine;

    /** @var string $name */
    protected $name;

    /** @var string $cacheDir */
    protected $cacheDir = "";

    /** @var string $IndentCharacter tab or space */
    protected $IndentCharacter = "space";

    /** @var int $IndentAmount */
    protected $IndentAmount = 4;

    /** @var bool $showComments */
    protected $showComments = true;

    /** @var string $extension */
    protected $extension = "php";

    /** @var string $variablePattern */
    protected $variablePattern = "{{%}}";

    /** @var VariablesBaseCache $variableCache */
    protected $variableCache;

    public function __construct(Engine $Engine)
    {
        $this->Engine = $Engine;
    }


    /**
     * turns this config into an array
     *
     * @return array
     */
    public function toArray()
    {
        $config = array();

        $config["name"] = $this->getName();
        $config["cacheDir"] = $this->getCacheDir();
        $config["indentCharacter"] = $this->getIndentCharacter();
        $config["indentAmount"] = $this->getIndentAmount();
        $config["showComments"] = $this->isShowComments();
        $config["extension"] = $this->getExtension();
        $config["variablePattern"] = $this->getVariablePattern();

        return $config;
    }


    /**
     * @return string
     * @throws Exception
     */
    public function getName()
    {
        if (is_null($this->name)) {
            throw new Exception(123123, "Please set a name for your language!");
        }

        return $this->name;
    }


    /**
     * @return string
     */
    public function getCacheDir()
    {

        if ($this->cacheDir == "" || gettype($this->cacheDir) != "string") {
            $this->cacheDir = $this->Engine->Config()->getCacheDir() . DIRECTORY_SEPARATOR . $this->name;
        }

        return $this->cacheDir . "/";
    }


    /**
     * @param $cacheDir
     *
     * @return string
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;

#        $this->update();

        return $this->cacheDir;
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

#        $this->update();

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

#        $this->update();

        return $this->IndentAmount;
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

#        $this->update();

        return $this->showComments;
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
     * @throws Exception
     */
    public function setExtension($extension)
    {
        $extension = $this->extension;

        if ($extension == "" || gettype($extension) != "string") {
            throw new Exception(1, "Invalid extension set for %" . get_class($this) . "%", __FILE__);
        }

#        $this->update();
        return $extension;
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
#        $this->update();
    }


    /**
     * @return VariablesBaseCache
     */
    public function getVariableCache()
    {
        return $this->variableCache;
    }


    /**
     * @param VariablesBaseCache $variableCache
     */
    public function setVariableCache($variableCache)
    {
        $this->variableCache = $variableCache;
    }


}