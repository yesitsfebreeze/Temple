<?php

namespace Pavel\Models;


use Pavel\EventManager\Event;
use Pavel\Exception\Exception;
use Pavel\Instance;


/**
 * Class Plugins
 *
 * @package Pavel
 */
class Plugin extends Event implements PluginInterface
{

    /** @var Instance $Instance */
    protected $Instance;


    function dispatch($args, Instance $Instance)
    {
        $this->Instance = $Instance;
        return $this->process($args);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strrev(explode("\\", strrev(get_class($this)))[0]);
    }

    /**
     * depending on the type of the plugin the element will one of those
     * line of template file, Node element, Dom element, parsed content
     *
     * @var mixed $element
     * @return mixed $element
     * @@throws Exception
     */
    public function process($element)
    {
        $name = $this->getName();
        throw new Exception("Please declare the method 'process' for the plugin '$name'!");
    }

}