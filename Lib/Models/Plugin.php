<?php

namespace Shift\Models;


use Shift\Exception\ShiftException;
use Shift\Instance;


/**
 * Class Plugins
 *
 * @package Shift
 */
class Plugin implements PluginInterface
{

    /** @var Instance $Shift */
    protected $Shift;


    /**
     * Plugin constructor.
     *
     * @param Instance $Shift
     */
    public function __construct(Instance $Shift)
    {
        $this->Shift = $Shift;
    }


    /**
     * @return integer
     */
    public function position()
    {
        return false;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return strrev(explode("\\", strrev(get_class($this)))[0]);
    }


    /**
     * @return bool
     */
    public function isPreProcessor()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDomProcessor()
    {
        return false;
    }


    /**
     * @return bool
     */
    public function isProcessor()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isFunction()
    {
        return false;
    }


    /**
     * @return bool
     */
    public function isPostProcessor()
    {
        return false;
    }


    /**
     * @return bool
     */
    public function isOutputProcessor()
    {
        return false;
    }


    /**
     * depending on the type of the plugin the element will one of those
     * line of template file, Node element, Dom element, parsed content
     *
     * @var mixed $element
     * @return mixed $element
     * @@throws ShiftException
     */
    public function process($element)
    {
        $name = $this->getName();
        throw new ShiftException("Please declare the method 'process' for the plugin '$name'!");
    }

}