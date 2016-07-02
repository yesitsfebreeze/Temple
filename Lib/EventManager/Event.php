<?php

namespace Pavel\EventManager;


use Pavel\Instance;


/**
 * Class Event
 */
abstract class Event
{
    /**
     * the method which gets fired when the observer notifies the assigned event
     *
     * @param mixed    $args
     * @param Instance $Instance
     *
     * @return mixed
     */
    abstract public function dispatch($args, Instance $Instance);
}