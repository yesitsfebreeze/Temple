<?php

namespace Temple\Engine\Languages;


use Temple\Engine\Cache\VariablesBaseCache;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Instance;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class LanguageConfig
{

    /** @var string $name */
    protected $Engine;

    /** @var string $name */
    private $name;

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
    protected $variableCache = null;


    public function __construct(Instance $Instance)
    {
        /** @var string $name */
        $this->name = $this->getLanguageName();

        $this->Instance = $Instance;
    }


    /**
     * turns this config into an array
     *
     * @return array
     */
    public function toArray()
    {
        $config = array();

        $config["name"]            = $this->getName();
        $config["cacheDir"]        = $this->getCacheDir();
        $config["indentCharacter"] = $this->getIndentCharacter();
        $config["indentAmount"]    = $this->getIndentAmount();
        $config["showComments"]    = $this->isShowComments();
        $config["extension"]       = $this->getExtension();
        $config["variablePattern"] = $this->getVariablePattern();

        return $config;
    }


    /**
     * turns this config into an array
     *
     * @param array $config
     *
     * @return LanguageConfig
     */
    public function createFromArray($config)
    {
        $this->name = $config["name"];
        $this->setCacheDir($config["cacheDir"]);
        $this->setIndentCharacter($config["indentCharacter"]);
        $this->setIndentAmount($config["indentAmount"]);
        $this->setShowComments($config["showComments"]);
        $this->setExtension($config["extension"]);
        $this->setVariablePattern($config["variablePattern"]);

        return $this;
    }


    /**
     * @return array|mixed|string
     */
    private function getLanguageName()
    {
        $name = get_class($this);
        $name = explode("\\", $name);
        array_pop($name);
        $name = end($name);
        $name = strtolower($name);

        return $name;
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
            $this->cacheDir = $this->Instance->Config()->getCacheDir() . DIRECTORY_SEPARATOR . $this->name;
        }

        $this->cacheDir = $this->Instance->DirectoryHandler()->getPath($this->cacheDir);

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
        if ($extension == "" || gettype($extension) != "string") {
            throw new Exception(1, "Invalid extension set for %" . get_class($this) . "%", __FILE__);
        }

        $this->extension = $extension;

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