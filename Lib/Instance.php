<?php

namespace Temple;


use Temple\Dependency\DependencyContainer;
use Temple\Exception\TempleException;
use Temple\Plugins\Plugins;
use Temple\Template\Lexer;
use Temple\Template\Parser;
use Temple\Template\Cache;
use Temple\Template\Template;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;

/**
 * Class Instance
 *
 * @package Temple
 */
class Instance
{

    /** @var  DependencyContainer $container */
    private $container;


    /**
     * Instance constructor.
     */
    public function __construct()
    {
        $this->container = new DependencyContainer();

        # Utilities
        new Config($this->container);
        new Directories($this->container);

        # Template
        new Plugins($this->container);
        new Parser($this->container);
        new Lexer($this->container);
        new Cache($this->container);
        new Template($this->container);
    }


    /**
     * @return Template
     * @throws TempleException
     */
    public function getTemplate()
    {
        return $this->container->getInstance("Template");
    }


    /**
     * @return Config
     * @throws TempleException
     */
    public function getConfig()
    {
        return $this->container->getInstance("Config");
    }


    /**
     * @return Plugins
     * @throws TempleException
     */
    public function getPlugins()
    {
        return $this->container->getInstance("Plugins");
    }


    /**
     * @return Cache
     * @throws TempleException
     */
    public function getCache()
    {
        return $this->container->getInstance("Cache");
    }

}