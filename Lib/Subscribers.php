<?php

namespace Underware;


use Underware\DependencyManager\DependencyInstance;
use Underware\EventManager\EventManager;
use Underware\Models\Nodes\FunctionNodeSubscriber;
use Underware\Models\Nodes\HtmlNodeSubscriber;
use Underware\Plugins\Node\Bricks;
use Underware\Plugins\Node\Classes;
use Underware\Plugins\Output\Cleanup;
use Underware\Plugins\Node\Comment;
use Underware\Plugins\Dom\Extend;
use Underware\Plugins\Node\Ids;
use Underware\Plugins\Node\Import;
use Underware\Plugins\Node\Php;
use Underware\Plugins\Node\Plain;
use Underware\Plugins\Node\Variables;


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
        $this->EventManager->attach("lexer.node", new FunctionNodeSubscriber());
        $this->EventManager->attach("lexer.node", new HtmlNodeSubscriber());
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