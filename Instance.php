<?php

namespace Shift;


use Shift\Dependency\DependencyContainer;
use Shift\Exception\ShiftException;
use Shift\Models\Variables;
use Shift\Template\Cache;
use Shift\Template\Lexer;
use Shift\Template\NodeFactory;
use Shift\Template\Parser;
use Shift\Template\PluginFactory;
use Shift\Template\Plugins;
use Shift\Template\Template;
use Shift\Utilities\Config;
use Shift\Utilities\Directories;


/**
 * Class Instance
 *
 * @package Shift
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
     * @throws ShiftException
     */
    public function template()
    {
        return $this->container->getInstance("Template/Template");
    }


    /**
     * @return Config
     * @throws ShiftException
     */
    public function config()
    {
        return $this->container->getInstance("Utilities/Config");
    }



    /**
     * @return Variables
     * @throws ShiftException
     */
    public function variables()
    {
        return $this->container->getInstance("Models/Variables");
    }


    /**
     * @return Plugins
     * @throws ShiftException
     */
    public function plugins()
    {
        return $this->container->getInstance("Template/Plugins");
    }


    /**
     * @return Cache
     * @throws ShiftException
     */
    public function cache()
    {
        return $this->container->getInstance("Template/Cache");
    }


    /**
     * @param string|null $config
     * @throws ShiftException
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