<?php

namespace Temple\Models\Variables;


use Temple\Utilities\Storage;


/**
 * Class Vars
 *
 * @package Temple
 */
class Variables extends Storage
{
    public function set($path, $value, $cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path, $value);
    }
}