<?php

namespace Temple\Engine\InjectionManager;


/**
 * Interface InjectionInterface
 *
 * @package Temple\Engine\Injection
 */
interface InjectionInterface
{

    /**
     * must be an array of the classes we want to implement
     *
     * @return array
     */
    function dependencies();


    /**
     * has to add an dependency instance to the class
     *
     * @param string    $name
     * @param Injection $dependency
     */
    function setDependency($name, Injection $dependency);


    /**
     * @param InjectionManager InjectionManager
     *
     * @return mixed
     */
    function setInjectionManager(InjectionManager $InjectionManager);
}