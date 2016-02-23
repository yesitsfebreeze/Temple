<?php

namespace Caramel;

class Helpers
{

    /**
     * Helpers constructor.
     * @param Caramel $caramel
     */
    public function __construct(Caramel $caramel)
    {
        $this->config = $caramel->config();
    }

    /**
     * searches a string for the needle and returns true if found
     *
     * @param string $string
     * @param string $needle
     * @return bool
     */
    public function find($string, $needle)
    {
        return sizeof(explode($needle, $string)) > 1;
    }

    /**
     * searches for a template file and returns the correct path
     *
     * @param string $file
     * @param string $parentFile
     * @return string $file
     */
    public function findTemplateFile($file, $parentFile)
    {

    }

}