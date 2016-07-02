<?php

namespace Pavel\Events;


use Pavel\Dependency\DependencyInstance;
use Pavel\EventManager\EventManager;
use Pavel\Events\Node\FunctionNode;
use Pavel\Events\Node\HtmlNode;
use Pavel\Events\Plugin\Bricks;
use Pavel\Events\Plugin\Classes;
use Pavel\Events\Plugin\Comment;
use Pavel\Events\Plugin\Extend;
use Pavel\Events\Plugin\Ids;
use Pavel\Events\Plugin\Php;
use Pavel\Events\Plugin\Plain;


/**
 * Class Events
 *
 * @package Pavel\Events
 */
class Events extends DependencyInstance
{

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @inheritdoc */
    public function dependencies()
    {
        return $this->getDependencies();
    }


    /**
     * registers all default subscribers
     */
    public function register()
    {
        $this->registerLexerEvents();
        $this->registerPluginEvents();
    }

    /**
     * register lexer subscribers
     */
    private function registerLexerEvents()
    {
        $this->EventManager->attach("lexer.node", new FunctionNode());
        $this->EventManager->attach("lexer.node", new HtmlNode());
    }


    /**
     * register plugin subscribers
     */
    private function registerPluginEvents()
    {
        $this->EventManager->attach("plugins.dom.process", new Extend());

        $this->EventManager->attach("plugins.node.process", new Plain());
        $this->EventManager->attach("plugins.node.process", new Comment());
        $this->EventManager->attach("plugins.node.process", new Bricks());
        $this->EventManager->attach("plugins.node.process", new Classes());
        $this->EventManager->attach("plugins.node.process", new Ids());
        $this->EventManager->attach("plugins.node.process", new Php());

//        $this->EventManager->attach("plugins.process", new Cleanup());

    }

}