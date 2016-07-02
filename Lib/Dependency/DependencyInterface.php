<?php

namespace Pavel\Dependency;


/**
 * Interface DependencyInterface
 * only used in DependencyInstance
 *
 * @package Pavel\DependencyManagement
 */
interface DependencyInterface
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
     * @param string             $name
     * @param DependencyInstance $instance
     */
    function setDependency($name, DependencyInstance $instance);
}