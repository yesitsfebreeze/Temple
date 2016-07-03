<?php

namespace Underware\Exception;


/**
 * Class Exception
 *
 * @package Underware
 */
class Exception extends \Exception
{

    /** @var bool|string $UnderwareFile */
    private $UnderwareFile;

    /** @var integer|string $UnderwareLine */
    private $UnderwareLine;


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


        if ($file) {
            $this->UnderwareFile = $file;
        }

        if ($line) {
            $this->UnderwareLine = $line;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }


    /**
     * returns the Underware file
     *
     * @return bool|string
     */
    public function getUnderwareFile()
    {
        return $this->UnderwareFile;
    }


    /**
     * returns the Underware line
     *
     * @return bool|int|string
     */
    public function getUnderwareLine()
    {
        return $this->UnderwareLine;
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