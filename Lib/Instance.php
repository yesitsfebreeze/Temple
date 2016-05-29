<?php

namespace Temple;


use Temple\Cache\Cache;
use Temple\DependencyManagement\DependencyContainer;
use Temple\Exceptions\TempleException;
use Temple\Plugins\Plugins;
use Temple\Template\Lexer;
use Temple\Template\Parser;
use Temple\Template\Template;

/**
 * Class Instance
 *
 * @package Temple
 */
class Instance
{

    /** @var  DependencyContainer $dependencyContainer */
    private $dependencyContainer;


    /**
     * Instance constructor.
     */
    public function __construct()
    {
        $this->dependencyContainer = new DependencyContainer();
        $this->load();
    }


    /**
     * create all the sub Instances
     */
    private function load()
    {
        new Config($this->dependencyContainer);
        new Cache($this->dependencyContainer);
        new Plugins($this->dependencyContainer);
        new Parser($this->dependencyContainer);
        new Lexer($this->dependencyContainer);
        new Template($this->dependencyContainer);
    }


    /**
     * @return DependencyManagement\DependencyInterface
     * @throws TempleException
     */
    public function getTemplate()
    {
        return $this->dependencyContainer->getInstance("Template");
    }

    /**
     * @return DependencyManagement\DependencyInterface
     * @throws TempleException
     */
    public function getConfig()
    {
        return $this->dependencyContainer->getInstance("Config");
    }

    /**
     * @return DependencyManagement\DependencyInterface
     * @throws TempleException
     */
    public function getPlugins()
    {
        return $this->dependencyContainer->getInstance("Plugins");
    }

    /**
     * @return DependencyManagement\DependencyInterface
     * @throws TempleException
     */
    public function getCache()
    {
        return $this->dependencyContainer->getInstance("Cache");
    }

}