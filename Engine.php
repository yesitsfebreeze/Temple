<?php

namespace Temple;


use Temple\Repositories\ServiceRepository;
use Temple\Services\CacheService;
use Temple\Services\ConfigService;
use Temple\Services\InitService;
use Temple\Services\PluginInitService;
use Temple\Services\TemplateService;


/**
 * Class Engine
 *
 * @package Temple
 */
class Engine
{

    /** @var ServiceRepository $services */
    private $services;


    /**
     * Engine constructor.
     */
    public function __construct()
    {
        $initService    = new InitService(__DIR__ . DIRECTORY_SEPARATOR);
        $this->services = $initService->getServices();
    }


    /**
     * @return ConfigService
     */
    public function Config()
    {
        return $this->services->get("configService");
    }


    /**
     * @return TemplateService
     */
    public function Template()
    {
        return $this->services->get("templateService");
    }


    /**
     * @return CacheService
     */
    public function Cache()
    {
        return $this->services->get("cacheService");
    }


    /**
     * @return PluginInitService
     */
    public function Plugins()
    {
        return $this->services->get("pluginInitService");
    }

}