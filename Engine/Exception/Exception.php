<?php

namespace Underware\Engine\Exception;


/**
 * Class Exception
 *
 * @package Underware
 */
class Exception extends \Exception
{

    /** @var bool|string $underwareMessage */
    private $underwareMessage;

    /** @var bool|string $underwareFile */
    private $underwareFile;

    /** @var integer|string $underwareLine */
    private $underwareLine;

    /** @var integer|string $underwareCode */
    private $underwareCode;


    public function __construct($code, $message = "", $file = false, $line = false, \Exception $previous = null)
    {

        # if we'v passed an exception, translate its values to the new one
        if ($message instanceof \Exception) {
            /** @var \Exception $exception */
            $exception = $message;
            $message   = $exception->getMessage();
            $code      = $exception->getCode();
            $previous  = $exception->getPrevious();
        }


        $this->underwareCode = $code;

        if ($file) {
            $this->underwareFile = $file;
        }

        if ($line) {
            $this->underwareLine = $line;
        }

        if ($message) {
            $this->underwareMessage = $message;
            $message                = str_replace("%", "", $message);
        } else {
            $message = "No Message given!";
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }

    /**
     * returns the Underware Exception Code
     *
     * @return bool|string
     */
    public function getUnderwareCode()
    {
        return $this->underwareCode;
    }

    /**
     * returns the Underware file
     *
     * @return bool|string
     */
    public function getUnderwareFile()
    {
        return $this->underwareFile;
    }


    /**
     * returns the Underware line
     *
     * @return bool|int|string
     */
    public function getUnderwareLine()
    {
        return $this->underwareLine;
    }


    /**
     * returns the Underware message
     *
     * @return bool|int|string
     */
    public function getUnderwareMessage()
    {
        return $this->underwareMessage;
    }


    /**
     * splits file into name and path
     *
     * @param $file
     * @param $root
     *
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
    function displayUnderwareErrorFile($root, $file, $line = false, $function = false)
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