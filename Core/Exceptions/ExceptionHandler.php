<?php

namespace Caramel\Exceptions;


class ExceptionHandler
{

    public function __construct()
    {

        $originalHandler = set_exception_handler(NULL);

        set_exception_handler(function (\Exception $Exception) use (&$originalHandler) {
            if ($Exception instanceof CaramelException) {

                include_once "Error/Error.php";
                return;
            } elseif (is_callable($originalHandler)) {
                return call_user_func_array($originalHandler, [$Exception]);
            }

            throw $Exception;
        });
    }

}



