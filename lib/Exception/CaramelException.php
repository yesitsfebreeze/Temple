<?php

namespace Caramel\Exception;

/**
 * Class CaramelException
 *
 * @package Caramel
 */
class CaramelException extends \Exception
{

    /** @var bool|string $CaramelFile */
    private $CaramelFile;

    /** @var integer|string $CaramelLine */
    private $CaramelLine;


    public function __construct($message = "", $file = false, $line = false, $code = 0, \Exception $previous = NULL)
    {

        # if we'v passed an exception, translate its values to the new one
        if ($message instanceof \Exception) {
            /** @var \Exception $exception */
            $exception = $message;
            $message   = $exception->getMessage();
            $code      = $exception->getCode();
            $previous  = $exception->getPrevious();
        }


        if ($file) {
            $this->CaramelFile = $file;
        }

        if ($line) {
            $this->CaramelLine = $line;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }


    /**
     * returns the Caramel file
     *
     * @return bool|string
     */
    public function getCaramelFile()
    {
        return $this->CaramelFile;
    }


    /**
     * returns the Caramel line
     *
     * @return bool|int|string
     */
    public function getCaramelLine()
    {
        return $this->CaramelLine;
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