<?php

namespace Temple\Utilities;


use Temple\Exception\TempleException;


/**
 * Class configFactory
 * @package Contentmanager\Services
 */
interface FactoryInterface
{

    /**
     * @param string $class
     * @return mixed
     * @throws TempleException
     */
    function create($class);


    /**
     * @param string $class
     * @return string|null
     * @throws TempleException
     */
    function check($class);

}