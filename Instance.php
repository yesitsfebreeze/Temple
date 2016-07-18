<?php

namespace Underware;


use Underware\Engine\Compiler;
use Underware\Engine\Config;
use Underware\Engine\Events\EventManager;
use Underware\Engine\Filesystem\Cache;
use Underware\Engine\Filesystem\DirectoryHandler;
use Underware\Engine\Filesystem\VariableCache;
use Underware\Engine\Injection\InjectionManager;
use Underware\Engine\Lexer;
use Underware\Engine\Structs\Variables;
use Underware\Engine\Template;
use Underware\Nodes\BlockNode;
use Underware\Nodes\CommentNode;
use Underware\Nodes\ForeachNode;
use Underware\Nodes\HtmlNode;
use Underware\Nodes\PlainNode;
use Underware\Nodes\VariableNode;


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

    /** @var VariableCache $VariableCache */
    private $VariableCache;

    /** @var Lexer $Lexer */
    private $Lexer;

    /** @var Compiler $Compiler */
    private $Compiler;

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
        $this->InjectionManager = new InjectionManager();
        $this->Config           = $this->InjectionManager->registerDependency(new Config());
        $this->Variables        = $this->InjectionManager->registerDependency(new Variables());
        $this->DirectoryHandler = $this->InjectionManager->registerDependency(new DirectoryHandler());
        $this->EventManager     = $this->InjectionManager->registerDependency(new EventManager());
        $this->Cache            = $this->InjectionManager->registerDependency(new Cache());
        $this->VariableCache    = $this->InjectionManager->registerDependency(new VariableCache());
        $this->Lexer            = $this->InjectionManager->registerDependency(new Lexer());
        $this->Compiler         = $this->InjectionManager->registerDependency(new Compiler());
        $this->Template         = $this->InjectionManager->registerDependency(new Template());

        $this->subscribers();

        return $this;
    }


    /**
     * default subscribers
     */
    public function subscribers()
    {
        $this->EventManager->setInstance($this);
        $this->EventManager->attach("lexer.node", new PlainNode());
        $this->EventManager->attach("lexer.node", new HtmlNode());
        $this->EventManager->attach("lexer.node", new CommentNode());
        $this->EventManager->attach("lexer.node", new BlockNode());
        $this->EventManager->attach("lexer.node", new ForeachNode());
        $this->EventManager->attach("lexer.node", new VariableNode());
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

}