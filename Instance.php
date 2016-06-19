<?php

namespace Temple;


use Temple\Dependency\DependencyContainer;
use Temple\Exception\TempleException;
use Temple\Models\Variables;
use Temple\Template\Cache;
use Temple\Template\Lexer;
use Temple\Template\NodeFactory;
use Temple\Template\Parser;
use Temple\Template\PluginFactory;
use Temple\Template\Plugins;
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

    /** @var Variables $Variables */
    private $Variables;

    /** @var Directories $Directories */
    private $Directories;

    /** @var  NodeFactory $NodeFactory */
    private $NodeFactory;

    /** @var  PluginFactory $PluginFactory */
    private $PluginFactory;

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
     * takes config path
     *
     * @param string|null $config
     */
    public function __construct($config = null)
    {
        $this->prepare($config);

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
     * @return Variables
     * @throws TempleException
     */
    public function Variables()
    {
        return $this->container->getInstance("Models/Variables");
    }


    /**
     * @return Plugins
     * @throws TempleException
     */
    public function Plugins()
    {
        return $this->container->getInstance("Template/Plugins");
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
     * @param string|null $config
     * @throws TempleException
     * @return bool
     */
    private function prepare($config)
    {
        $this->container = new DependencyContainer();

        $this->Config        = $this->container->registerDependency(new Config());
        $this->Variables     = $this->container->registerDependency(new Variables());
        $this->Directories   = $this->container->registerDependency(new Directories());
        $this->NodeFactory   = $this->container->registerDependency(new NodeFactory());
        $this->PluginFactory = $this->container->registerDependency(new PluginFactory());
        $this->Plugins       = $this->container->registerDependency(new Plugins());
        $this->Parser        = $this->container->registerDependency(new Parser());
        $this->Lexer         = $this->container->registerDependency(new Lexer());
        $this->Cache         = $this->container->registerDependency(new Cache());
        $this->Template      = $this->container->registerDependency(new Template());

        # this is the only place were a dependency setter is used
        # the whole instance will be passed into the plugins
        $this->PluginFactory->setInstance($this);

        # Setup
        $this->Config->addConfigFile(__DIR__ . "/config.php");
        if (file_exists($config)) $this->Config->addConfigFile($config);
        $this->Plugins->addDirectory(__DIR__ . "/Plugins");
        $this->Cache->setDirectory($this->Config->get("dirs.cache"));

        return true;
    }


}