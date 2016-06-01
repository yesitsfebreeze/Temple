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
        $this->container->registerDependency(new Config());
        $this->container->registerDependency(new Directories());

        # Template
        $this->container->registerDependency(new Plugins());
        $this->container->registerDependency(new Parser());
        $this->container->registerDependency(new Lexer());
        $this->container->registerDependency(new Cache());
        $this->container->registerDependency(new Template());
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