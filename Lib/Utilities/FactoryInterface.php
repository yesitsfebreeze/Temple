<?php

namespace Temple\Utilities;

/**
 * Class configFactory
 * @package Contentmanager\Services
 */
interface FactoryInterface
{

    /**
     * @param string $class
     * @return mixed
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