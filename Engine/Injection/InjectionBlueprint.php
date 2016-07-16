<?php

namespace Underware\Engine\Injection;


/**
 * Interface Blueprint
 *
 * @package Underware\Engine\Injection
 */
interface InjectionBlueprint
{

    /**
     * must be an array of the classes we want to implement
     *
     * @return array
     */
    function dependencies();


    /**
     * has to add an instance to the class

     *
*@param string         $name
     * @param Injection $dependency
     */
    function setDependency($name, Injection $dependency);
}