<?php

namespace Temple\Dependency;


use Temple\Exception\TempleException;


/**
 * Class DependencyContainer
 *
 * @package Temple\DependencyManagement
 */
class DependencyContainer
{

    /** @var array $dependencies */
    private $dependencies = array();

    private $rootNameSpace = "Temple";


    /**
     * adds dependencies if existing
     *
     * @param DependencyInterface $instance
     * @throws TempleException
     * @return DependencyInstance
     */
    public function registerDependency(DependencyInterface &$instance)
    {
        # check if the dependencies method exists
        # the Instance does net extend the DependencyInterface class if not
        if (!method_exists($instance, "dependencies")) {
            return null;
        }
        # get all dependencies and add them to the Instance
        $dependencies = $instance->dependencies();
        $this->setDependencies($instance, $dependencies);

        # register the instance in our container
        $name = $this->cleanClassNamespace($instance);
        $this->dependencies[$name] = $instance;
        
        return $instance;
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
        if (!isset($this->dependencies[$name])) {
            throw new TempleException("Dependency Management: " . "$name is not instantiated yet.");    
        }

        return $this->dependencies[$name];
    }
    

    /**
     * adds a list of dependencies to an instance
     *
     * @param $instance
     * @param $dependencies
     * @throws TempleException
     */
    private function setDependencies(DependencyInterface &$instance, $dependencies)
    {
        if (!is_array($dependencies)) {
            throw new TempleException("Dependency Management: dependencies() must return an array ", get_class($instance) . ".php");
        }

        foreach ($dependencies as $dependency => $name) {
            $this->setDependency($instance, $dependency, $name);
        }
    }


    /**
     * @param DependencyInterface $instance
     * @param string $dependency
     * @param $name
     * @throws TempleException
     */
    private function setDependency(DependencyInterface &$instance, $dependency, $name)
    {
        if (!isset($this->dependencies[$dependency])) {
            throw new TempleException("Dependency Management: " . $dependency . " instance does't exist.", get_class($instance));
            
        }

        $instance->setDependency($name, $this->dependencies[$dependency]);
    }


    /**
     * returns the name of a Class
     *
     * @param DependencyInterface $instance
     * @return mixed
     */
    private function cleanClassNamespace(DependencyInterface $instance)
    {
        $name = get_class($instance);
        $name = str_replace("\\", "/", $name);
        $name = str_replace($this->rootNameSpace . "/", "", $name);
        return $name;
    }

}