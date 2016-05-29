<?php

namespace Temple\BaseClasses;


use Temple\Repositories\StorageRepository;


/**
 * Class Vars
 *
 * @package Temple
 */
class VarsBaseClass extends StorageRepository
{
    public function set($path,$value,$cached = false)
    {
        // TODO: cache per session or cookie
        parent::set($path,$value);
    }
}