<?php

namespace Temple\Dependency;


use Temple\Exceptions\TempleException;


/**
 * Class DependencyContainer
 *
 * @package Temple\DependencyManagement
 */
class DependencyContainer
{

    /** @var array $dependencies */
    private $dependencies = array();


    /**
     * loads dependencies if existing
     *
     * @param DependencyInterface $instance
     * @throws TempleException
     */
    public function load(DependencyInterface &$instance)
    {
        $dependencies = $instance->dependencies();
        if (is_array($dependencies)) {
            foreach ($dependencies as $dependency) {
                $this->setDependency($instance, $dependency);
            }
        } else {
            throw new TempleException("Dependency Management: dependencies() must return an array ", get_class($instance) . ".php");
        }
    }


    /**
     * @param DependencyInterface $instance
     * @param                     $dependency
     * @throws TempleException
     */
    public function setDependency(DependencyInterface &$instance, $dependency)
    {
        if (isset($this->dependencies[ $dependency ])) {
            $instance->setDependency($dependency, $this->dependencies[ $dependency ]);
        } else {
            throw new TempleException("Dependency Management: " . $dependency . " instance does't exist.");
        }
    }


    /**
     * @param DependencyInterface $instance
     */
    public function registerDependency(DependencyInterface $instance)
    {
        $name                        = $this->getClassName($instance);
        $this->dependencies[ $name ] = $instance;
    }


    /**
     * returns an instance if it's set
     *
     * @param string $name
     * @return DependencyInterface
     * @throws TempleException
     */
    public function getInstance($name)
    {
        if (isset($this->dependencies[ $name ])) {
            return $this->dependencies[ $name ];
        }
        throw new TempleException("Dependency Management: " . "$name is not instantiated yet.");
    }


    /**
     * returns the name of a Class
     *
     * @param DependencyInterface $instance
     * @return mixed
     */
    private function getClassName(DependencyInterface $instance)
    {
        return array_pop(explode("\\", get_class($instance)));
    }

}