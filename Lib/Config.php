<?php

namespace Temple;


use Temple\DependencyManagement\DependencyContainer;
use Temple\DependencyManagement\DependencyInstance;
use Temple\Repositories\Storage;

class Config extends DependencyInstance
{

    /** @var Storage $config */
    private $config;

    public function dependencies()
    {
        return array();
    }

    public function __construct(DependencyContainer $dependencyContainer)
    {
        parent::__construct($dependencyContainer);
        $this->config = new Storage();
    }

}