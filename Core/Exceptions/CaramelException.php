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

    /** @var bool|string $caramelFile */
    private $caramelFile;

    /** @var integer|string $caramelLine */
    private $caramelLine;


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
            $this->caramelFile = $file;
        }

        if ($line) {
            $this->caramelLine = $line;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }


    /**
     * returns the caramel file
     *
     * @return bool|string
     */
    public function getCaramelFile()
    {
        return $this->caramelFile;
    }


    /**
     * returns the caramel line
     *
     * @return bool|int|string
     */
    public function getCaramelLine()
    {
        return $this->caramelLine;
    }


    /**
     * splits file into name and path
     *
     * @param $file
     * @param $root
     * @return array $file
     */
    private function splitFile($file, $root)
    {
        $tempFile     = $file;
        $file         = array();
        $temp         = explode("/", $tempFile);
        $file["name"] = array_pop($temp);
        $file["path"] = str_replace($root . "/", "", implode("/", $temp) . "/");

        return $file;
    }


    /**
     * displays an exception file
     *
     * @param $file
     * @param $root
     * @param $line
     * @param $function
     */
    function displayCaramelErrorFile($root, $file, $line = false, $function = false)
    {

        $file = $this->splitFile($file, $root);
        echo $file["path"] . "<b>" . $file["name"] . "</b>";

        if ($line) {
            echo " in line " . "<b>" . $line . "</b>";
        }
        if ($function) {
            echo " in function " . "<b>" . $function . "</b>";
        }
    }


}