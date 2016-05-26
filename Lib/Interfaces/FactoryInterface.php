<?php

namespace Caramel\Interfaces;

/**
 * Class configFactory
 * @package Contentmanager\Services
 */
interface FactoryInterface
{

    /**
     * @param string $class
     * @return NodeInterface|null
     * @throws \Exception
     */
    function create($class);


    /**
     * @param string $class
     * @return string|null
     * @throws \Exception
     */
    function check($class);

}