<?php

namespace Underware\Engine\Injection;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Exception\Handler;


/**
 * Class Manager
 *
 * @package Underware\Engine\Injection
 */
class InjectionManager
{

    /** @var array $dependencies */
    public $dependencies = array();


    /**
     * adds dependencies if existing
     *
     * @param InjectionInterface $instance
     *
     * @throws Exception
     * @return mixed
     */
    public function registerDependency(InjectionInterface &$instance)
    {
        # check if the dependencies method exists
        # the Instance does net extend the InjectionInterface class if not
        if (!method_exists($instance, "dependencies")) {
            return null;
        }

        # get all dependencies and add them to the Instance
        $dependencies = $instance->dependencies();
        $this->setDependencies($instance, $dependencies);

        # register the instance in our container
        $name                        = $this->cleanClassNamespace($instance);
        $this->dependencies[ $name ] = $instance;

        $instance->setInjectionManager($this);

        return $instance;
    }


    /**
     * returns an instance if it's set
     *
     * @param string $name
     *
     * @return InjectionInterface
     * @throws Exception
     */
    public function getInstance($name)
    {

        if (!isset($this->dependencies[ $name ])) {
            new Handler();
            throw new Exception("Injection Management: %" . $name . "% is not instantiated yet.");
        }

        return $this->dependencies[ $name ];
    }


    /**
     * @param $name
     *
     * @return bool
     */
    public function checkInstance($name)
    {
        if (!isset($this->dependencies[ $name ])) {
            return false;
        }

        return true;
    }


    /**
     * adds a list of dependencies to an instance
     *
     * @param $instance
     * @param $dependencies
     *
     * @throws Exception
     */
    private function setDependencies(InjectionInterface &$instance, $dependencies)
    {
        if (!is_array($dependencies)) {
            new Handler();
            throw new Exception("Injection Management: %dependencies()% must return an array ", get_class($instance) . ".php");
        }

        foreach ($dependencies as $dependency => $name) {
            $this->setDependency($instance, $dependency, $name);
        }
    }


    /**
     * @param InjectionInterface  $instance
     * @param string              $dependency
     * @param                     $name
     *
     * @throws Exception
     */
    private function setDependency(InjectionInterface &$instance, $dependency, $name)
    {

        if (!isset($this->dependencies[ $dependency ])) {
            new Handler();
            throw new Exception("Injection Management: %" . $dependency . "% instance does't exist.", get_class($instance));
        }

        $instance->setDependency($name, $this->dependencies[ $dependency ]);
    }


    /**
     * returns the name of a Class
     *
     * @param InjectionInterface $instance
     *
     * @return mixed
     */
    private function cleanClassNamespace(InjectionInterface $instance)
    {
        $className = str_replace("\\", "/", get_class($instance));
        $root      = strrev(substr(strrchr(strrev($className), "/"), 1));
        $className = str_replace($root . "/", "", $className);

        return $className;
    }

}