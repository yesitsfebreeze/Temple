<?php

namespace Caramel\Exceptions;


use Exception as Exception;

/**
 * Class CaramelException
 *
 * @package Caramel
 */
class CaramelException extends Exception
{

    public function __construct($message = "", $file = false, $line = false, $code = 0, Exception $previous = NULL)
    {

        # if we'v passed an exception, translate its values to the new one
        if ($message instanceof Exception) {
            /** @var Exception $exception */
            $exception = $message;
            $message   = $exception->getMessage();
            $code      = $exception->getCode();
            $previous  = $exception->getPrevious();
        }

        if ($file) {
            $this->file = $file;
        }

        if ($line) {
            $this->line = $file;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }

}