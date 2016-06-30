<?php

namespace Shift\Utilities;


use Shift\Exception\ShiftException;


/**
 * Class configFactory
 * @package Contentmanager\Services
 */
interface FactoryInterface
{

    /**
     * @param string $class
     * @return mixed
     * @throws ShiftException
     */
    function create($class);


    /**
     * @param string $class
     * @return string|null
     * @throws ShiftException
     */
    function check($class);

}