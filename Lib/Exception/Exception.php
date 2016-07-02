<?php

namespace Pavel\Exception;


/**
 * Class Exception
 *
 * @package Pavel
 */
class Exception extends \Exception
{

    /** @var bool|string $PavelFile */
    private $PavelFile;

    /** @var integer|string $PavelLine */
    private $PavelLine;


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
            $this->PavelFile = $file;
        }

        if ($line) {
            $this->PavelLine = $line;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }


    /**
     * returns the Pavel file
     *
     * @return bool|string
     */
    public function getPavelFile()
    {
        return $this->PavelFile;
    }


    /**
     * returns the Pavel line
     *
     * @return bool|int|string
     */
    public function getPavelLine()
    {
        return $this->PavelLine;
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
    function displayPavelErrorFile($root, $file, $line = false, $function = false)
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