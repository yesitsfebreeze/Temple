<?php

namespace Underware\EventManager;


use Underware\DependencyManager\DependencyInstance;
use Underware\Instance;
use Underware\Utilities\Storage;


/**
 * Class EventManager
 *
 * @package Underware\EventManager
 */
class EventManager extends DependencyInstance
{

    /** @inheritdoc */
    public function dependencies()
    {
        return array();
    }


    /**
     * @var \Underware\Utilities\Storage $events
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
     * @param $args
     *
     * @return mixed
     * @throws \Underware\Exception\Exception
     */
    public function notify($event, $args)
    {
        if (!$this->events->has($event)) {
            $this->events->set($event, true);

            return $args;
        } else {
            return $this->dispatch($this->events->get($event), $args);
        }
    }


    /**
     * @param mixed $event
     * @param mixed $args
     *
     * @return mixed $args
     */
    private function dispatch($event, $args)
    {

        /** @var Event $event */
        if (is_array($event)) {
            foreach ($event as $e) {
                $args = $this->dispatch($e, $args);
            }
        } else {
            if (is_object($event)) {
                $args = $event->dispatch($args, $this->instance);
            }
        }

        return $args;
    }


    /**
     * @param            $event
     * @param integer    $position
     * @param Event $subscriber
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
