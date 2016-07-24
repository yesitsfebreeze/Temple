<?php

namespace Underware\Engine;


use Underware\Engine\EventManager\EventManager;
use Underware\Engine\Filesystem\Cache;
use Underware\Engine\Filesystem\CacheInvalidator;
use Underware\Engine\Filesystem\DirectoryHandler;
use Underware\Engine\Filesystem\VariableCache;
use Underware\Engine\Injection\InjectionManager;
use Underware\Engine\Structs\Variables;


/**
 * Class Instance
 *
 * @package Underware
 */
class Instance
{

    /** @var InjectionManager $InjectionManager */
    private $InjectionManager;

    /** @var Config $Config */
    private $Config;

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
        $config = ($config === null) ? new Config() : $config;

        $this->InjectionManager = new InjectionManager();
        $this->Config           = $this->InjectionManager->registerDependency($config);
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