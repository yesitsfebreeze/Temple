<?php

namespace Temple\Engine\EventManager;


use Temple\Engine\Exception\Exception;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Instance;
use Temple\Engine\Structs\Storage;


/**
 * Class Manager
 *
 * @package Temple\Manager
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
     * Manager constructor.
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
     * @return Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }


    /**
     * @param string $language
     * @param string $event
     * @param mixed  $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public function dispatch($language, $event, $arguments = null)
    {
        $events = array();
        if ($this->events->has("core" . "." . $event)) {
            $events = array_merge($events, $this->events->get("core" . "." . $event));
        }
        if ($this->events->has($language . "." . $event)) {
            $events = array_merge($events, $this->events->get($language . "." . $event));
        }

        if (sizeof($events) == 0) {
            return $arguments;
        } else {
            return $this->dispatchEvent($events, $arguments);
        }
    }


    /**
     * @param mixed $events
     * @param mixed $arguments
     *
     * @return mixed $arguments
     */
    private function dispatchEvent($events, $arguments)
    {
        /** @var Event $event */
        if (is_array($events)) {
            foreach ($events as $event) {
                $arguments = $this->dispatchEvent($event, $arguments);
            }
        } elseif (is_object($events)) {
            $arguments = $this->executeEvent($events, $arguments);
        }

        return $arguments;
    }


    /**
     * @param Event $event
     * @param       $arguments
     *
     * @return array
     * @throws Exception
     */
    private function executeEvent(Event $event, $arguments)
    {
        if (is_object($event)) {
            $eventInstance = clone $event;
            $eventInstance->setInstance($this->instance);
            $eventInstance->setInjectionManager($this->InjectionManager);

            if (!is_array($arguments)) {
                $arguments = array($arguments);
            } elseif (is_null($arguments)) {
                $arguments = array();
            } elseif (is_array($arguments) && sizeof($arguments) == 0) {
                $arguments = array($arguments);
            }

            /** @noinspection PhpMethodParametersCountMismatchInspection */
            if (!method_exists($eventInstance, "dispatch")) {
                $class = get_class($eventInstance);
                throw new Exception(1, "Please register the %dispatch% method for %" . $class . "%");
            }

            $arguments = $eventInstance->dispatch(...$arguments);
            unset($eventInstance);
        }

        return $arguments;
    }


    /**
     * @param string $language
     * @param string $event
     * @param Event  $subscriber
     *
     * @return bool
     */
    public function subscribe($language, $event, Event $subscriber)
    {
        $this->events->set($language . "." . $event, $subscriber);

        return true;
    }


    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function unsubscribe($eventName)
    {

        if (!$this->events->has($eventName)) {
            return false;
        }

        $this->events->delete($eventName);

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
        $events = $this->events->get($selector);

        return $events;
    }

}
