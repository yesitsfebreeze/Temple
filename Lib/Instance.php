<?php

namespace Underware;


use Underware\DependencyManager\DependencyContainer;
use Underware\EventManager\EventManager;
use Underware\Exception\Exception;
use Underware\Models\Variables;
use Underware\Template\Cache;
use Underware\Template\Lexer;
use Underware\Template\Parser;
use Underware\Template\Template;
use Underware\Utilities\Config;
use Underware\Utilities\Directories;


/**
 * Class Instance
 *
 * @package Underware
 */
class Instance
{

    /** @var  DependencyContainer $container */
    private $container;

    /** @var Config $Config */
    private $Config;

    /** @var EventManager $EventManager */
    private $EventManager;

    /** @var Variables $Variables */
    private $Variables;

    /** @var Directories $Directories */
    private $Directories;

    /** @var Parser $Parser */
    private $Parser;

    /** @var Lexer $Lexer */
    private $Lexer;

    /** @var Cache $Cache */
    private $Cache;

    /** @var Template $Template */
    private $Template;

    /** @var Subscribers $Subscribers */
    private $Subscribers;


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
     * @throws Exception
     */
    public function Template()
    {
        return $this->container->getInstance("Template/Template");
    }


    /**
     * @return Config
     * @throws Exception
     */
    public function Config()
    {
        return $this->container->getInstance("Utilities/Config");
    }


    /**
     * @return Variables
     * @throws Exception
     */
    public function Variables()
    {
        return $this->container->getInstance("Models/Variables");
    }


    /**
     * @return EventManager
     * @throws Exception
     */
    public function EventManager()
    {
        return $this->container->getInstance("EventManager/EventManager");
    }


    /**
     * @return Cache
     * @throws Exception
     */
    public function Cache()
    {
        return $this->container->getInstance("Template/Cache");
    }


    /**
     * @param string|null $config
     *
     * @throws Exception
     * @return bool
     */
    private function prepare($config)
    {
        $this->container = new DependencyContainer();

        $this->Config       = $this->container->registerDependency(new Config());
        $this->EventManager = $this->container->registerDependency(new EventManager());
        $this->Directories  = $this->container->registerDependency(new Directories());
        $this->Parser       = $this->container->registerDependency(new Parser());
        $this->Lexer        = $this->container->registerDependency(new Lexer());
        $this->Variables    = $this->container->registerDependency(new Variables());
        $this->Cache        = $this->container->registerDependency(new Cache());
        $this->Template     = $this->container->registerDependency(new Template());
        $this->Subscribers  = $this->container->registerDependency(new Subscribers());

        $this->Config->addConfigFile(__DIR__ . "/../config.php");
        if (file_exists($config)) $this->Config->addConfigFile($config);
        $this->Cache->setDirectory($this->Config->get("dirs.cache"));

        $this->EventManager->setInstance($this);
        $this->Subscribers->attachEvents();

        return true;
    }


}