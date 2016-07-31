<?php

namespace Rite\Engine\Structs;

use Rite\Engine\Exception\Exception;


class Page {


    /** @var  string $fileName */
    private $fileName;

    /** @var  string $file */
    private $file;


    /** @var  Variables $Variables */
    private $Variables;


    /**
     * shows the page
     *
     * @return string
     * @throws Exception
     */
    public function display()
    {
        if (!is_null($this->file)) {
            /** @noinspection PhpIncludeInspection */
            return include $this->file;
        } else {
            throw new Exception(1,"Missing cache file!",$this->getFileName());
        }
    }


    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }


    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }


    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


    /**
     * @return Variables
     */
    public function getVariables()
    {
        return $this->Variables;
    }


    /**
     * @param Variables $Variables
     */
    public function setVariables($Variables)
    {
        $this->Variables = $Variables;
    }


}