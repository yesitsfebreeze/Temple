<?php


namespace Temple\Engine\Console;


use Temple\Engine\Exception\Exception;


/**
 * Class Console
 *
 * @package Temple\Engine\Console
 */
class Command
{

    /** @var CliOutput $CliOutput */
    protected $CliOutput;

    /** @var CliProgress $CliProgress */
    protected $CliProgress;

    /** @var bool $useProgress */
    private $useProgress;

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


}