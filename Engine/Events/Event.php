<?php

namespace Underware\Engine\Events;


use Underware\Engine\Exception\Exception;
use Underware\Instance;


/**
 * Class Event
 */
abstract class Event
{

    /** @var Instance $Instance */
    protected $Instance;

    /**
     * the method which gets fired when the event manager notifies the assigned event
     *
     * @param array $arguments
     *
     * @return bool
     * @throws Exception
     */
    public function dispatch($arguments)
    {
        $class = get_class($this);
        throw new Exception("Please register the %dispatch% method for %" . $class . "%");
    }


    /**
     * @param Instance $Instance
     */
    public function setInstance(Instance $Instance)
    {
        $this->Instance = $Instance;
    }

}