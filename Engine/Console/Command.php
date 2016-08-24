<?php


namespace Temple\Engine\Console;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Storage;


/**
 * Class Console
 *
 * @package Temple\Engine\Console
 */
class Command
{

    /** @var Storage $Storage */
    protected $Storage;

    /** @var CliOutput $CliOutput */
    protected $CliOutput;

    /** @var CliProgress $CliProgress */
    protected $CliProgress;

    /** @var bool $useProgress */
    private $useProgress = false;

    /** @var string $progressTitle */
    private $progressTitle = "";

    /** @var string $progressTitleColor */
    private $progressTitleColor = null;

    /** @var string $progressTitleBackground */
    private $progressTitleBackground = null;

    /** @var bool $useConfigs */
    private $useConfigs = true;

    /** @var  array $config */
    protected $config;

    /** @var  string $name */
    private $name;

    /** @var  string $className */
    private $className;

    /** @var  string $path */
    private $path;


    /**
     * just sets the path to the console command file for the cache
     * Command constructor.
     */
    public function __construct()
    {
        $this->path      = __FILE__;
        $this->className = get_class($this);
    }


    /**
     * define the commands info
     *
     * @throws Exception
     */
    public function define()
    {
        $commandName = preg_replace('/^.*?\[^\]*?$/', "", get_class($this));
        throw new Exception(5000, "Please implement the %define% function for %" . $commandName . "%!");
    }


    /**
     * the code which runs when you fire the command
     * $args is just a placeholder
     * in your command you get each argument as a single parameter
     *
     * @param $args
     *
     * @throws Exception
     */
    public function execute($args)
    {
        unset($args);
        $commandName = preg_replace('/^.*?\[^\]*?$/', "", get_class($this));
        throw new Exception(5000, "Please implement the %execute% function for %" . $commandName . "%!");
    }


    /**
     * the code which runs after your got command fired
     *
     * @throws Exception
     */
    public function after()
    {
    }


    /**
     * add help to your cli buffer
     *
     * @throws Exception
     */
    public function getHelp()
    {
    }


    /**
     * @param CliOutput $CliOutput
     */
    public function setCliOutput($CliOutput)
    {
        $this->CliOutput = $CliOutput;
    }


    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * @return boolean
     */
    public function isUseProgress()
    {
        return $this->useProgress;
    }


    /**
     * @param boolean $useProgress
     */
    public function setUseProgress($useProgress)
    {
        $this->useProgress = $useProgress;
    }


    /**
     * @return boolean
     */
    public function isUseConfigs()
    {
        return $this->useConfigs;
    }


    /**
     * @param boolean $useConfigs
     */
    public function setUseConfigs($useConfigs)
    {
        $this->useConfigs = $useConfigs;
    }


    /**
     * @param boolean $progressTitle
     */
    public function setProgressTitle($progressTitle)
    {
        $this->progressTitle = $progressTitle;
    }


    /**
     * @return string
     */
    public function getProgressTitle()
    {
        return $this->progressTitle;
    }


    /**
     * @return CliProgress
     */
    public function getCliProgress()
    {
        return $this->CliProgress;
    }


    /**
     * @param CliProgress $CliProgress
     */
    public function setCliProgress($CliProgress)
    {
        $this->CliProgress = $CliProgress;
    }


    /**
     * @return Storage
     */
    public function getStorage()
    {
        return $this->Storage;
    }


    /**
     * @param Storage $Storage
     */
    public function setStorage($Storage)
    {
        $this->Storage = $Storage;
    }


    /**
     * @return string
     */
    public function getProgressTitleColor()
    {
        return $this->progressTitleColor;
    }


    /**
     * @param string $progressTitleColor
     */
    public function setProgressTitleColor($progressTitleColor)
    {
        $this->progressTitleColor = $progressTitleColor;
    }


    /**
     * @return string
     */
    public function getProgressTitleBackground()
    {
        return $this->progressTitleBackground;
    }


    /**
     * @param string $progressTitleBackground
     */
    public function setProgressTitleBackground($progressTitleBackground)
    {
        $this->progressTitleBackground = $progressTitleBackground;
    }


}