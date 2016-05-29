<?php

namespace Temple;


use Temple\Plugins\Plugins;
use Temple\Template\Template;


/**
 * Class Engine
 *
 * @package Temple
 */
class Engine
{

    /** @var Instance $instance */
    private $instance;


    /**
     * Engine constructor.
     */
    public function __construct()
    {
        $this->instance = new Instance();
    }


    /**
     * @return Template
     */
    public function Template()
    {
        return $this->instance->getTemplate();
    }


    /**
     * @return Config
     */
    public function Config()
    {
        return $this->instance->getConfig();
    }


    /**
     * @return Plugins
     */
    public function Plugins()
    {
        return $this->instance->getPlugins();
    }


    /**
     * @return Cache
     */
    public function Cache()
    {
        return $this->instance->getCache();
    }

}