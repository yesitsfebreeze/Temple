<?php

namespace Caramel;


use Caramel\Repositories\ServiceRepository;
use Caramel\Services\InitService;


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
        $initService    = new InitService(__DIR__ . "/../");
        $this->services = $initService->getServices();
    }


    /**
     * @return mixed
     */
    public function Config()
    {
        return $this->services->get("config");
    }


    /**
     * @return mixed
     */
    public function Template()
    {
        return $this->services->get("template");
    }


    /**
     * @return mixed
     */
    public function Cache()
    {
        return $this->services->get("cache");
    }


    /**
     * @return mixed
     */
    public function Plugins()
    {
        return $this->services->get("plugins");
    }

}