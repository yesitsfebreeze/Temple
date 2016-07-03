<?php

namespace Underware\Models;


use Underware\Utilities\Storage;


/**
 * Class Vars
 *
 * @package Underware
 */
class Variables extends Storage
{
    public function set($path, $value, $cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path, $value);
    }
}