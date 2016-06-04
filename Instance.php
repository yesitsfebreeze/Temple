<?php

namespace Temple;


use Temple\Dependency\DependencyContainer;
use Temple\Exception\TempleException;
use Temple\Template\Cache;
use Temple\Template\Lexer;
use Temple\Template\NodeFactory;
use Temple\Template\Parser;
use Temple\Template\Plugins\PluginFactory;
use Temple\Template\Plugins\Plugins;
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

    /** @var Config $Config */
    private $Config;

    /** @var Directories $Directories */
    private $Directories;

    /** @var Plugins $Plugins */
    private $Plugins;

    /** @var Parser $Parser */
    private $Parser;

    /** @var Lexer $Lexer */
    private $Lexer;

    /** @var Cache $Cache */
    private $Cache;

    /** @var Template $Template */
    private $Template;


    /**
     * Instance constructor.
     */
    public function __construct()
    {
        $this->instantiate();

        return $this;
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


    /**
     * @throws TempleException
     */
    private function instantiate()
    {
        $this->container = new DependencyContainer();

        # Utilities
        $this->Config      = $this->container->registerDependency(new Config());
        $this->Directories = $this->container->registerDependency(new Directories());

        # Template
        $this->Plugins  = $this->container->registerDependency(new Plugins(new PluginFactory()));
        $this->Parser   = $this->container->registerDependency(new Parser());
        $this->Lexer    = $this->container->registerDependency(new Lexer(new NodeFactory()));
        $this->Cache    = $this->container->registerDependency(new Cache());
        $this->Template = $this->container->registerDependency(new Template());

        # this is the only place were a dependency setter is used
        # the whole instance will be passed into the plugins
        $this->Plugins->setTempleInstance($this);

        # Setup
        $this->Config->addConfigFile(__DIR__ . "/config.php");
        $this->Plugins->addDirectory(__DIR__ . "/Plugins");
        $this->Cache->setDirectory($this->Config->get("dirs.cache"));  # that is wrong

        return true;
    }


}