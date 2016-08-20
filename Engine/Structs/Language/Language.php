<?php

namespace Temple\Engine\Structs\Language;


use Temple\Engine\Config;
use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;


class Language extends Event
{

    /** @var Config $Config */
    private $Config;

    /** @var string $name */
    private $name;

    /** @var string $privateCacheFolder */
    private $privateCacheFolder;

    public function __construct(Config $Config)
    {
        $this->Config = $Config;
        $name = explode("\\", get_class($this));
        array_pop($name);
        $this->name = strtolower(end($name));
    }


    /**
     * @param $args
     */
    public function dispatch($args)
    {
        $this->register();
    }


    /**
     * set the extension for the language
     *
     * @return string
     * @throws Exception
     */
    public function extension()
    {
        throw new Exception(1, "Please implement the extension function for %" . get_class($this) . "%", __FILE__);
    }


    /**
     * set the folder which the parsed files will be saved to, default is the global cache directory
     *
     * @return string
     * @throws Exception
     */
    public function cacheFolder()
    {
        return false;
    }


    /**
     * this function is used to register all nodes and plugins for the language
     * return void
     *
     * @throws Exception
     */
    protected function register()
    {
        throw new Exception(1, "Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }

    /**
     * returns the current language extension
     */
    public function getExtension()
    {
        $extension = $this->extension();

        if ($extension == "" || gettype($extension) != "string") {
            throw new Exception(1, "Invalid extension set for %" . get_class($this) . "%", __FILE__);
        }

        return $extension;
    }


    /**
     * returns the current language cache folder
     */
    public function getCacheFolder()
    {
        if ($this->privateCacheFolder != false) {
            $folder = $this->privateCacheFolder;
        } else {
            $folder = $this->cacheFolder();
        }

        if ($folder == "" || gettype($folder) != "string") {
            $folder = $this->Config->getCacheDir() . DIRECTORY_SEPARATOR . $this->name;
        }

        return $folder;
    }


    /**
     * @param $cacheFolder
     */
    public function setCacheFolder($cacheFolder)
    {
        $this->privateCacheFolder = $cacheFolder;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



}