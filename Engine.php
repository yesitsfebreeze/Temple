<?php

namespace Caramel;


use Caramel\Repositories\ServiceRepository;
use Caramel\Services\CacheService;
use Caramel\Services\ConfigService;
use Caramel\Services\InitService;
use Caramel\Services\PluginService;
use Caramel\Services\TemplateService;


/**
 * Class Engine
 *
 * @package Caramel
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
        return $this->services->get("config");
    }


    /**
     * @return TemplateService
     */
    public function Template()
    {
        return $this->services->get("template");
    }


    /**
     * @return CacheService
     */
    public function Cache()
    {
        return $this->services->get("cache");
    }


    /**
     * @return PluginService
     */
    public function Plugins()
    {
        return $this->services->get("plugins");
    }

}