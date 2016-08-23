<?php

namespace Temple\Engine\Structs;


use Temple\Engine\Cache\VariablesBaseCache;
use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;
use Temple\Engine;


class Language
{

    /** @var Engine $Engine */
    protected $Engine;

    /** @var string $name */
    private $name;

    /** @var string $privateCacheFolder */
    private $privateCacheFolder;

    /** @var string $variableCache */
    private $variableCache = false;


    public function __construct(Engine $Engine)
    {

        // todo: add this Event to the cache invalidator to clear the cache if we change a node
        $this->Engine = $Engine;
        $name           = explode("\\", get_class($this));
        array_pop($name);
        $this->name          = strtolower(end($name));
        $this->variableCache = $this->variableCache();
        if ($this->variableCache instanceof VariablesBaseCache) {
            $this->variableCache->setEngine($this->Engine);
        }
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
    public function register()
    {
        throw new Exception(1, "Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }


    /**
     * this function is used to register all nodes and plugins for the language
     * return VariablesBaseCache | false
     */
    protected function variableCache()
    {
        return false;
    }


    /**
     * subscribes an event into the scoped language
     *
     * @param       $name
     * @param Event $event
     */
    public function subscribe($name, Event $event)
    {
        // todo: add this Event to the cache invalidator to clear the cache if we change a node
        $this->Engine->EventManager()->subscribe($this->getName(),$name, $event);
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
            $folder = $this->Engine->Config()->getCacheDir() . DIRECTORY_SEPARATOR . $this->name;
        }

        return $folder . "/";
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


    /**
     * @return string
     */
    public function getVariableCache()
    {
        return $this->variableCache;
    }


}