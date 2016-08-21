<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\InjectionManager;
use Temple\Engine\Instance;


/**
 * Class Event
 */
abstract class Event
{

    /** @var Instance $Instance */
    protected $Instance;


    /** @var InjectionManager $InjectionManager */
    protected $InjectionManager;


    /**
     * @param Instance $Instance
     */
    public function setInstance(Instance $Instance)
    {
        $this->Instance = $Instance;
    }


    /**
     * @param InjectionManager $InjectionManager
     */
    public function setInjectionManager(InjectionManager $InjectionManager)
    {
        $this->InjectionManager = $InjectionManager;
    }

}