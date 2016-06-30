<?php

namespace Shift\Models;


use Shift\Utilities\Storage;


/**
 * Class Vars
 *
 * @package Shift
 */
class Variables extends Storage
{
    public function set($path, $value, $cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path, $value);
    }
}