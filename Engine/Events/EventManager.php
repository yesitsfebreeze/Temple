<?php

namespace Underware\Engine\Events;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;
use Underware\Engine\Structs\Storage;
use Underware\Instance;


/**
 * Class EventManager
 *
 * @package Underware\EventManager
 */
class EventManager extends Injection
{

    /** @inheritdoc */
    public function dependencies()
    {
        return array();
    }


    /**
     * @var Storage
     */
    private $events;

    /**
     * @var Instance $instance
     */
    private $instance;


    /**
     * EventManager constructor.
     */
    public function __construct()
    {
        $this->events = new Storage();
    }


    /**
     * sets the current Instance
     *
     * @param Instance $instance
     */
    public function setInstance(Instance $instance)
    {
        $this->instance = $instance;
    }


    /**
     * @param $event
     * @param $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public function notify($event, $arguments = null)
    {
        if (!$this->events->has($event)) {
            return $arguments;
        } else {
            return $this->dispatch($this->events->get($event), $arguments);
        }
    }


    /**
     * @param mixed $events
     * @param mixed $arguments
     *
     * @return mixed $arguments
     */
    private function dispatch($events, $arguments)
    {
        /** @var Event $event */
        if (is_array($events)) {
            foreach ($events as $event) {
                $arguments = $this->dispatch($event, $arguments);
            }
        } elseif (is_object($events)) {
            $arguments = $this->realDispatch($events, $arguments);
        }

        return $arguments;
    }


    /**
     * @param Event $event
     * @param       $arguments
     *
     * @return array|bool
     */
    public function realDispatch(Event $event, $arguments)
    {
        if (is_object($event)) {
            $eventInstance = clone $event;
            $eventInstance->setInstance($this->instance);

            if (!is_array($arguments)) {
                $arguments = array($arguments);
            } elseif (is_null($arguments)) {
                $arguments = array();
            }
            $arguments = $eventInstance->dispatch(...$arguments);
            unset($eventInstance);
        }

        return $arguments;
    }


    /**
     * @param            $event
     * @param integer    $position
     * @param Event      $subscriber
     *
     * @return bool
     */
    public function attach($event, Event $subscriber, $position = null)
    {

        if (!$this->events->has($event . ".pos")) {
            $this->events->set($event . ".pos", array());
        }

        if (!is_null($position)) {
            $selector = $event . ".pos." . $position;
            if (!$this->events->has($selector)) {
                $this->events->set($selector, array());
            }
            $events = $this->events->get($selector);
            array_unshift($events, $subscriber);
            $this->events->set($selector, $events);
        } else {
            $selector = $event . ".pos";
            $events   = $this->events->get($selector);
            array_unshift($events, array($subscriber));
            $this->events->set($selector, $events);
        }

        return true;
    }


    /**
     * @param            $event
     *
     * @return bool
     */
    public function detach($event)
    {

        if (!$this->events->has($event)) {
            return false;
        }

        $this->events->delete($event);

        return true;
    }


    /**
     * returns all available events
     *
     * @param string|null $selector
     *
     * @return array
     */
    public function getEvents($selector = null)
    {
        $events     = $this->events->get($selector);
        $eventsCopy = $events;
        $events     = $this->cleanEvents($eventsCopy);
        $events     = $this->flattenEvents($events);

        return $events;
    }


    /**
     * only returns an array with event names
     * rather than the complete event array
     *
     * @param             $events
     *
     * @return mixed
     */
    private function cleanEvents(&$events)
    {

        if (is_object($events)) {
            return true;
        } elseif (is_array($events)) {
            foreach ($events as $key => &$event) {
                if ($key == "pos") {
                    $event = true;
                } else {
                    $event = $this->cleanEvents($event);
                }
            }
        }

        return $events;
    }


    /**
     * returns all event as a flattened array
     *
     * @param        $events
     * @param string $prefix
     *
     * @return array
     */
    private function flattenEvents($events, $prefix = "")
    {
        $flatted = array();
        foreach ($events as $key => $value) {
            if (is_array($value)) {
                $flatted = array_merge($flatted, $this->flattenEvents($value, $prefix . $key . '.'));
            } else {
                if ($key == "pos") {
                    $flatted[ substr($prefix, 0, -1) ] = $value;
                } else {
                    $flatted[ $prefix . $key ] = $value;
                }
            }
        }

        return $flatted;
    }

}
