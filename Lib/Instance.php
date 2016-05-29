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

    /** @var  DependencyContainer $dependencyContainer */
    private $dependencyContainer;


    /**
     * Instance constructor.
     */
    public function __construct()
    {
        $this->dependencyContainer = new DependencyContainer();

        # Utilities
        new Config($this->dependencyContainer);
        new Directories($this->dependencyContainer);

        # Template
        new Plugins($this->dependencyContainer);
        new Parser($this->dependencyContainer);
        new Lexer($this->dependencyContainer);
        new Cache($this->dependencyContainer);
        new Template($this->dependencyContainer);
    }


    /**
     * @return Template
     * @throws TempleException
     */
    public function getTemplate()
    {
        return $this->dependencyContainer->getInstance("Template");
    }


    /**
     * @return Config
     * @throws TempleException
     */
    public function getConfig()
    {
        return $this->dependencyContainer->getInstance("Config");
    }


    /**
     * @return Plugins
     * @throws TempleException
     */
    public function getPlugins()
    {
        return $this->dependencyContainer->getInstance("Plugins");
    }


    /**
     * @return Cache
     * @throws TempleException
     */
    public function getCache()
    {
        return $this->dependencyContainer->getInstance("Cache");
    }

}