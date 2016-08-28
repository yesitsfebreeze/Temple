<?php

namespace Temple;


use Temple\Engine\Cache\CommandCache;
use Temple\Engine\Cache\ConfigCache;
use Temple\Engine\Cache\TemplateCache;
use Temple\Engine\Compiler;
use Temple\Engine\Config;
use Temple\Engine\Console\Console;
use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine\Instance;
use Temple\Engine\Languages\Languages;
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

    /** @var TemplateCache $TemplateCache */
    protected $TemplateCache;

    /** @var CommandCache $CommandCache */
    protected $CommandCache;

    /** @var Languages $Languages */
    protected $Languages;

    /** @var Lexer $Lexer */
    protected $Lexer;

    /** @var Compiler $Compiler */
    protected $Compiler;

    /** @var Template $Template */
    protected $Template;

    /** @var Instance $Instance */
    protected $Instance;


    /**
     * Engine constructor.
     *
     * @param Config|null $config
     * @param int $identifier
     */
    public function __construct(Config $config = null, $identifier = 0)
    {
        // used passed config or default
        $config = ($config instanceof Config) ? $config : new Config();

        $this->InjectionManager = new InjectionManager($config);

        $this->Config           = $this->InjectionManager->registerDependency($config);
        $this->DirectoryHandler = $this->InjectionManager->registerDependency(new DirectoryHandler());
        $this->CommandCache     = $this->InjectionManager->registerDependency(new CommandCache());
        $this->Console          = $this->InjectionManager->registerDependency(new Console());
        $this->EventManager     = $this->InjectionManager->registerDependency(new EventManager());
        $this->Variables        = $this->InjectionManager->registerDependency(new Variables());
        $this->Languages        = $this->InjectionManager->registerDependency(new Languages());
        $this->ConfigCache      = $this->InjectionManager->registerDependency(new ConfigCache());
        $this->TemplateCache    = $this->InjectionManager->registerDependency(new TemplateCache());
        $this->Lexer            = $this->InjectionManager->registerDependency(new Lexer());
        $this->Compiler         = $this->InjectionManager->registerDependency(new Compiler());
        $this->Template         = $this->InjectionManager->registerDependency(new Template());
        $this->Instance         = $this->InjectionManager->registerDependency(new Instance());

        $this->init();

        return $this;
    }


    /**
     * initiates some stuff
     */
    protected function init()
    {
        // set up unique id for the object
        if (!isset($GLOBALS["TempleInstanceCounter"])) {
            $GLOBALS["TempleInstanceCounter"] = 0;
        }
        $GLOBALS["TempleInstance"] = $GLOBALS["TempleInstanceCounter"]++;

        $this->Config->setInstance($this->Instance);
        $this->Config->setIdentifier($GLOBALS["TempleInstance"]);
        $this->EventManager->setInstance($this->Instance);
        $this->Languages->setInstance($this->Instance);
        $this->Config->update();
        $this->Languages->initLanguages();
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
     * @return TemplateCache
     */
    public function Cache()
    {
        return $this->TemplateCache;
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