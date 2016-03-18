<?php

namespace Caramel\Exceptions;


/**
 * Class ExceptionHandler
 *
 * @package Caramel\Exceptions
 */
class ExceptionHandler
{

    /**
     * adds a global exception handler for caramel exceptions
     * ExceptionHandler constructor.
     */
    public function __construct()
    {

        /** @var $originalHandler */
        $originalHandler = set_exception_handler(NULL);

        set_exception_handler(function ($Exception) use (&$originalHandler) {
            if ($Exception instanceof CaramelException) {
                # include the error template
                die(include_once "Error/Error.php");

            } elseif (is_callable($originalHandler)) {
                return call_user_func_array($originalHandler, [$Exception]);
            }

            throw $Exception;
        });
    }

}



