<?php


namespace Temple\Dependency;


use Temple\Exception\TempleException;

abstract class DependencyInstance implements DependencyInterface
{

    /**
     * has to add an instance to the class
     *
     * @param string             $name
     * @param DependencyInstance $instance
     * @throws TempleException
     */
    public function setDependency($name, DependencyInstance $instance)
    {
        if (property_exists($this, $name)) {
            $this->$name = $instance;
        } else {
            throw new TempleException("Dependency Management: Please register 'protected $$name'", get_class($this) . ".php");
        }

    }

}