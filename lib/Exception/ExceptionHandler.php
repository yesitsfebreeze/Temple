<?php

namespace Caramel\Exception;



/**
 * Class ExceptionHandler
 *
 * @package Caramel\Exceptions
 */
class ExceptionHandler
{

    /**
     * adds a global exception handler for Caramel exceptions
     * ExceptionHandler constructor.
     */
    public function __construct()
    {

        /** @var $originalHandler */
        $originalHandler = set_exception_handler(NULL);

        set_exception_handler(function ($Exception) use (&$originalHandler) {
            if ($Exception instanceof CaramelException) {


                # display exception stuff here
                echo "<div><b>" .$Exception->getMessage() . "</b></div>";
                die("exception");

            } elseif (is_callable($originalHandler)) {
                return call_user_func_array($originalHandler, [$Exception]);
            }

            throw $Exception;
        });
    }

}



