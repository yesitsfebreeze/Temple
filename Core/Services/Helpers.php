<?php

namespace Caramel;

class Helpers {


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

}