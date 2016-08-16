<?php

namespace Temple;


use Temple\Engine\Compiler;
use Temple\Engine\Config;
use Temple\Engine\Console\Console;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\Cache;
use Temple\Engine\Filesystem\CacheInvalidator;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\Filesystem\VariableCache;
use Temple\Engine\InjectionManager\InjectionManager;
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
    private $InjectionManager;

    /** @var Config $Config */
    private $Config;

    /** @var Console $Console */
    private $Console;

    /** @var Variables $Variables */
    private $Variables;

    /** @var DirectoryHandler $DirectoryHandler */
    private $DirectoryHandler;

    /** @var EventManager $EventManager */
    private $EventManager;

    /** @var Cache $Cache */
    private $Cache;

    /** @var CacheInvalidator $CacheInvalidator */
    private $CacheInvalidator;

    /** @var VariableCache $VariableCache */
    private $VariableCache;

    /** @var Languages $Languages */
    private $Languages;

    /** @var Lexer $Lexer */
    private $Lexer;

    /** @var Compiler $Compiler */
    private $Compiler;

    /** @var Template $Template */
    private $Template;


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
        $this->Console          = $this->InjectionManager->registerDependency(new Console());
        $this->Variables        = $this->InjectionManager->registerDependency(new Variables());
        $this->DirectoryHandler = $this->InjectionManager->registerDependency(new DirectoryHandler());
        $this->EventManager     = $this->InjectionManager->registerDependency(new EventManager());
        $this->Cache            = $this->InjectionManager->registerDependency(new Cache());
        $this->CacheInvalidator = $this->InjectionManager->registerDependency(new CacheInvalidator());
        $this->VariableCache    = $this->InjectionManager->registerDependency(new VariableCache());
        $this->Languages        = $this->InjectionManager->registerDependency(new Languages());
        $this->Lexer            = $this->InjectionManager->registerDependency(new Lexer());
        $this->Compiler         = $this->InjectionManager->registerDependency(new Compiler());
        $this->Template         = $this->InjectionManager->registerDependency(new Template());


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

}