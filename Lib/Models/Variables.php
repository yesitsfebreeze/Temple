<?php

namespace Pavel\Models;


use Pavel\Utilities\Storage;


/**
 * Class Vars
 *
 * @package Pavel
 */
class Variables extends Storage
{
    public function set($path, $value, $cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path, $value);
    }
}