<?php

namespace WorkingTitle\Engine\EventManager;


use WorkingTitle\Engine\Exception\Exception;
use WorkingTitle\Engine\InjectionManager\InjectionManager;
use WorkingTitle\Engine\Instance;


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
     * the method which gets fired when the event manager notifies the assigned event
     *
     * @param $arguments
     *
     * @throws Exception
     * @noinspection PhpUnusedParameterInspection
     */
    public function dispatch(/** @noinspection PhpUnusedParameterInspection  */ $arguments)
    {
        $class = get_class($this);
        throw new Exception(1, "Please register the %dispatch% method for %" . $class . "%");

    }


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