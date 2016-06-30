<?php


namespace Shift\Dependency;


use Shift\Exception\ShiftException;


abstract class DependencyInstance implements DependencyInterface
{

    /**
     * has to add an instance to the class
     *
     * @param string             $name
     * @param DependencyInstance $instance
     * @throws ShiftException
     */
    public function setDependency($name, DependencyInstance $instance)
    {
        if (!property_exists($this, $name)) {
            throw new ShiftException("Dependency Management: Please register 'protected $$name'", get_class($this) . ".php");
        }

        $this->$name = $instance;
    }

}