<?php

namespace Temple\Models;


use Temple\Repositories\StorageRepository;


/**
 * Class Vars
 *
 * @package Temple
 */
class Vars extends StorageRepository
{
    public function set($path,$value,$cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path,$value);
    }
}