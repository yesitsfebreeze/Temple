<?php

namespace Caramel\Models;


/**
 * Class Vars
 *
 * @package Caramel
 */
class Vars extends Storage
{
    public function set($path,$value,$cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path,$value);
    }
}