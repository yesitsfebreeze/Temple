<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine;


/**
 * Class Event
 */
abstract class Event
{

    /** @var Engine $Engine */
    protected $Engine;


    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;


    /**
     * @param Engine $Engine
     */
    public function setEngine(Engine $Engine)
    {
        $this->Engine = $Engine;
    }


    /**
     * @param InjectionManager $InjectionManager
     */
    public function setInjectionManager(InjectionManager $InjectionManager)
    {
        $this->InjectionManager = $InjectionManager;
    }

}