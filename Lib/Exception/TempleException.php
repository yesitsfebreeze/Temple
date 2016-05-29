<?php

namespace Temple\Exception;


/**
 * Class TempleException
 *
 * @package Temple
 */
class TempleException extends \Exception
{

    /** @var bool|string $TempleFile */
    private $TempleFile;

    /** @var integer|string $TempleLine */
    private $TempleLine;


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
            $this->TempleFile = $file;
        }

        if ($line) {
            $this->TempleLine = $line;
        }

        # execute the default exception after that
        parent::__construct($message, $code, $previous);

    }


    /**
     * returns the Temple file
     *
     * @return bool|string
     */
    public function getTempleFile()
    {
        return $this->TempleFile;
    }


    /**
     * returns the Temple line
     *
     * @return bool|int|string
     */
    public function getTempleLine()
    {
        return $this->TempleLine;
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
    function displayTempleErrorFile($root, $file, $line = false, $function = false)
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