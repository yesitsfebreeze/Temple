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


    /** @var  string $rootPath */
    private $rootPath;

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
        $this->path = __FILE__;
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
     *
     * @throws Exception
     */
    public function execute()
    {
        $commandName = preg_replace('/^.*?\[^\]*?$/', "", get_class($this));
        throw new Exception(5000, "Please implement the %execute% function for %" . $commandName . "%!");
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




}