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

    /** @var  array $attributes */
    protected $attrs;


    function dispatch($args, Instance $Instance)
    {
        $this->Instance = $Instance;
        $this->attrs    = $this->attributes();
        if (sizeof($this->attrs) > 0) {
            $this->generateNodeAttributes($args);
        }

        return $this->process($args);
    }


    public function attributes()
    {
        return array();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return strrev(explode("\\", strrev(get_class($this)))[0]);
    }


    public function generateNodeAttributes($args)
    {
        if (is_array($args)) {
            foreach ($args as $arg) {
                $this->generateAttributes($arg);
            }
        } else {
            $this->generateAttributes($args);
        }
    }


    private function generateAttributes($node)
    {
        if ($node instanceof BaseNode) {
            $attributes = array();
            $count      = 0;
            foreach ($node->get("attributes") as $name => $value) {
                if ($value == "") {
                    $value = $name;
                }
                if (isset($this->attrs[ $count ])) {
                    $name                = $this->attrs[ $count ];
                    $attributes[ $name ] = $value;
                    $count               = $count + 1;
                }
            }
            $this->attrs = $attributes;
        }
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