<?php

namespace Pavel\Models;


use Pavel\Instance;


/**
 * Class Plugins
 *
 * @package Pavel
 */
interface PluginInterface
{

    /**
     * @param          $args
     * @param Instance $Instance
     *
     * @return mixed
     */
    public function dispatch($args, Instance $Instance);


    /**
     * @param mixed $element
     *
     * @return mixed
     */
    public function process($element);

}