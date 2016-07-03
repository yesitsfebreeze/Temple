<?php

namespace Underware;


use Underware\DependencyManager\DependencyInstance;
use Underware\EventManager\EventManager;
use Underware\Nodes\FunctionNode;
use Underware\Nodes\HtmlNode;
use Underware\Plugins\Bricks;
use Underware\Plugins\Classes;
use Underware\Plugins\Cleanup;
use Underware\Plugins\Comment;
use Underware\Plugins\Extend;
use Underware\Plugins\Ids;
use Underware\Plugins\Import;
use Underware\Plugins\Php;
use Underware\Plugins\Plain;
use Underware\Plugins\Variables;


class Subscribers extends DependencyInstance
{

    /** @var  EventManager $EventManager */
    protected $EventManager;


    /**
     * @throws Exception\Exception
     */
    public function dependencies()
    {
        return $this->getDependencies();
    }


    /**
     * register default events
     */
    public function attachEvents()
    {
        $this->attachNodes();
        $this->attachPlugins();
    }


    /**
     * register nodes
     */
    private function attachNodes()
    {
        $this->EventManager->attach("lexer.node", new FunctionNode());
        $this->EventManager->attach("lexer.node", new HtmlNode());
    }


    /**
     * register all plugins
     */
    private function attachPlugins()
    {
        $this->EventManager->attach("plugin.dom", new Extend());
        $this->EventManager->attach("plugin.node", new Plain());
        $this->EventManager->attach("plugin.node", new Comment());
        $this->EventManager->attach("plugin.node", new Bricks());
        $this->EventManager->attach("plugin.node", new Classes());
        $this->EventManager->attach("plugin.node", new Ids());
        $this->EventManager->attach("plugin.node", new Php());
        $this->EventManager->attach("plugin.node", new Import());
        $this->EventManager->attach("plugin.node", new Variables());
        $this->EventManager->attach("plugin.output", new Cleanup());
    }


}