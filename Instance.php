<?php

namespace Temple;


use Temple\Dependency\DependencyContainer;
use Temple\Exception\TempleException;
use Temple\Plugins\Plugins;
use Temple\Template\Cache;
use Temple\Template\Lexer;
use Temple\Template\Parser;
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

    /** @var Config $config */
    private $config;

    /** @var Directories $directories */
    private $directories;

    /** @var Plugins $plugins */
    private $plugins;

    /** @var Parser $parser */
    private $parser;

    /** @var Lexer $lexer */
    private $lexer;

    /** @var Cache $cache */
    private $cache;

    /** @var Template $template */
    private $template;


    /**
     * Instance constructor.
     */
    public function __construct()
    {
        $this->container = new DependencyContainer();

        # Utilities
        $this->config      = $this->container->registerDependency(new Config());
        $this->directories = $this->container->registerDependency(new Directories());

        # Template
        $this->plugins  = $this->container->registerDependency(new Plugins());
        $this->parser   = $this->container->registerDependency(new Parser());
        $this->lexer    = $this->container->registerDependency(new Lexer());
        $this->cache    = $this->container->registerDependency(new Cache());
        $this->template = $this->container->registerDependency(new Template());

        $this->config->addConfigFile(__DIR__ . "/config.php");
        $this->plugins->addDirectory(__DIR__ . "/Plugins");
        $this->plugins->initiatePlugins();

    }


    /**
     * @return Template
     * @throws TempleException
     */
    public function Template()
    {
        return $this->container->getInstance("Template/Template");
    }


    /**
     * @return Config
     * @throws TempleException
     */
    public function Config()
    {
        return $this->container->getInstance("Utilities/Config");
    }


    /**
     * @return Plugins
     * @throws TempleException
     */
    public function Plugins()
    {
        return $this->container->getInstance("Plugins/Plugins");
    }


    /**
     * @return Cache
     * @throws TempleException
     */
    public function Cache()
    {
        return $this->container->getInstance("Template/Cache");
    }

}