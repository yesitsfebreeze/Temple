<?php

namespace Temple;


use Temple\Engine\Cache\Cache;
use Temple\Engine\Cache\CacheInvalidator;
use Temple\Engine\Cache\ConfigCache;
use Temple\Engine\Compiler;
use Temple\Engine\Config;
use Temple\Engine\Console\CommandCache;
use Temple\Engine\Console\Console;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine\EngineWrapper;
use Temple\Engine\Languages;
use Temple\Engine\Lexer;
use Temple\Engine\Structs\Variables;
use Temple\Engine\Template;


/**
 * Class Engine
 *
 * @package Temple
 */
class Engine extends Injection
{

    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;

    /** @var Config $Config */
    protected $Config;

    /** @var ConfigCache $ConfigCache */
    protected $ConfigCache;

    /** @var Console $Console */
    protected $Console;

    /** @var Variables $Variables */
    protected $Variables;

    /** @var DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var EventManager $EventManager */
    protected $EventManager;

    /** @var Cache $Cache */
    protected $Cache;

    /** @var CommandCache $CommandCache */
    protected $CommandCache;

    /** @var CacheInvalidator $CacheInvalidator */
    protected $CacheInvalidator;

    /** @var Languages $Languages */
    protected $Languages;

    /** @var Lexer $Lexer */
    protected $Lexer;

    /** @var Compiler $Compiler */
    protected $Compiler;

    /** @var Template $Template */
    protected $Template;

    /** @var EngineWrapper $EngineWrapper */
    protected $EngineWrapper;


    /**
     * Engine constructor.
     *
     * @param Config|null $config
     */
    public function __construct(Config $config = null)
    {

        // used passed config or default
        $config = ($config instanceof Config) ? $config : new Config();

        $this->InjectionManager = new InjectionManager();

        $this->Config           = $this->InjectionManager->registerDependency($config);
        $this->ConfigCache      = $this->InjectionManager->registerDependency(new ConfigCache());
        $this->CommandCache     = $this->InjectionManager->registerDependency(new CommandCache());
        $this->Console          = $this->InjectionManager->registerDependency(new Console());
        $this->DirectoryHandler = $this->InjectionManager->registerDependency(new DirectoryHandler());
        $this->EventManager     = $this->InjectionManager->registerDependency(new EventManager());
        $this->Variables        = $this->InjectionManager->registerDependency(new Variables());
        $this->Languages        = $this->InjectionManager->registerDependency(new Languages());
        $this->Cache            = $this->InjectionManager->registerDependency(new Cache());
        $this->CacheInvalidator = $this->InjectionManager->registerDependency(new CacheInvalidator());
        $this->Lexer            = $this->InjectionManager->registerDependency(new Lexer());
        $this->Compiler         = $this->InjectionManager->registerDependency(new Compiler());
        $this->Template         = $this->InjectionManager->registerDependency(new Template());
        $this->EngineWrapper  = $this->InjectionManager->registerDependency(new EngineWrapper());

        $this->Config->setEngineWrapper($this->EngineWrapper);
        $this->EventManager->setEngine($this);
        $this->Languages->setEngine($this);

        return $this;
    }


    /**
     * @return Config
     */
    public function Config()
    {
        return $this->Config;
    }


    /**
     * @return Console
     */
    public function Console()
    {
        return $this->Console;
    }


    /**
     * @return Variables
     */
    public function Variables()
    {
        return $this->Variables;
    }


    /**
     * @return Template
     */
    public function Template()
    {
        return $this->Template;
    }


    /**
     * @return Cache
     */
    public function Cache()
    {
        return $this->Cache;
    }


    /**
     * @return EventManager
     */
    public function EventManager()
    {
        return $this->EventManager;
    }


    /**
     * @return Languages
     */
    public function Languages()
    {
        return $this->Languages;
    }

}