<?php

namespace Underware\Engine\Exception;


/**
 * Class Exception
 *
 * @package Underware
 */
class ValidateException extends Exception
{


    public function __construct($message = "", $file = false, $line = false, $code = 0, \Exception $previous = null)
    {

        # if we'v passed an exception, translate its values to the new one
        if ($message instanceof \Exception) {
            /** @var \Exception $exception */
            $exception = $message;
            $message   = $exception->getMessage();
            $code      = $exception->getCode();
            $previous  = $exception->getPrevious();
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);
    }

}