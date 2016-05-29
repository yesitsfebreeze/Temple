<?php

namespace Temple;


use Temple\Services\CacheService;
use Temple\Services\ConfigService;
use Temple\Services\DependencyService;
use Temple\Services\PluginInitService;
use Temple\Services\TemplateService;


/**
 * Class Engine
 *
 * @package Temple
 */
class Engine
{

    /** @var DependencyService $classes */
    private $dependencies;


    /**
     * Engine constructor.
     */
    public function __construct()
    {
        $this->dependencies = new DependencyService(__DIR__ . DIRECTORY_SEPARATOR, $this);
    }


    /**
     * @return ConfigService
     */
    public function Config()
    {
        return $this->dependencies->getConfigService();
    }


    /**
     * @return TemplateService
     */
    public function Template()
    {
        return $this->dependencies->getTemplateService();
    }


    /**
     * @return CacheService
     */
    public function Cache()
    {
        return $this->dependencies->getCacheService();
    }


    /**
     * @return PluginInitService
     */
    public function Plugins()
    {
        return $this->dependencies->getPluginInitService();
    }

}