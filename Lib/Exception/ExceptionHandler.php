<?php

namespace Temple\Exception;


/**
 * Class ExceptionHandler
 *
 * @package Temple\Exceptions
 */
class ExceptionHandler
{

    /**
     * adds a global exception handler for Temple exceptions
     * ExceptionHandler constructor.
     */
    public function __construct()
    {

        $originalHandler = set_exception_handler(null);

        set_exception_handler(function ($Exception) use (&$originalHandler) {

            if ($Exception instanceof TempleException) {
                new ExceptionTemplate($Exception);
            } elseif (is_callable($originalHandler)) {
                return call_user_func_array($originalHandler, [$Exception]);
            }

            throw $Exception;
        });

    }

}



