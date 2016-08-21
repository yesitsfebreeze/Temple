<?php

namespace Temple\Engine;


use Temple\Engine\Compiler;
use Temple\Engine\Config;
use Temple\Engine\Console\CommandCache;
use Temple\Engine\Console\Console;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\Cache;
use Temple\Engine\Filesystem\CacheInvalidator;
use Temple\Engine\Filesystem\ConfigCache;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\Filesystem\VariableCache;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine\InstanceWrapper;
use Temple\Engine\Languages;
use Temple\Engine\Lexer;
use Temple\Engine\Structs\Variables;
use Temple\Engine\Template;


/**
 * Class Instance
 *
 * @package Temple
 */
class Instance
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

    /** @var CacheInvalidator $CacheInvalidator */
    protected $CacheInvalidator;

    /** @var VariableCache $VariableCache */
    protected $VariableCache;

    /** @var Languages $Languages */
    protected $Languages;

    /** @var Lexer $Lexer */
    protected $Lexer;

    /** @var Compiler $Compiler */
    protected $Compiler;

    /** @var Template $Template */
    protected $Template;


    /**
     * Instance constructor.
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
        $this->Languages        = $this->InjectionManager->registerDependency(new Languages());
        $this->Cache            = $this->InjectionManager->registerDependency(new Cache());
        $this->CacheInvalidator = $this->InjectionManager->registerDependency(new CacheInvalidator());
        $this->Lexer            = $this->InjectionManager->registerDependency(new Lexer());
        $this->Compiler         = $this->InjectionManager->registerDependency(new Compiler());
        $this->Template         = $this->InjectionManager->registerDependency(new Template());
        $this->InstanceWrapper  = $this->InjectionManager->registerDependency(new InstanceWrapper());

        $this->Config->setInstanceWrapper($this->InstanceWrapper);
        $this->EventManager->setInstance($this);

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