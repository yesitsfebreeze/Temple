<?php


namespace Temple\DependencyManagement;


use Temple\Exceptions\TempleException;

abstract class DependencyInstance implements DependencyInterface
{

    /**
     * DependencyInstance constructor.
     *
     * @param DependencyContainer $dependencyContainer
     */
    public function __construct(DependencyContainer $dependencyContainer)
    {
        $dependencyContainer->load($this);
        $dependencyContainer->registerDependency($this);
    }


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