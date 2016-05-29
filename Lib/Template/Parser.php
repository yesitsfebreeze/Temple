<?php

namespace Temple\Template;


use Temple\Cache\Cache;
use Temple\DependencyManagement\DependencyInstance;

/**
 * Class Parser
 *
 * @package Temple
 */
class Parser extends DependencyInstance
{

    /** @var  Cache $Cache */
    protected $Cache;


    public function dependencies()
    {
        return array(
            "Cache"
        );
    }

}