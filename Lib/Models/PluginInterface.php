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
    function dispatch($args, Instance $Instance);


    /**
     * @param mixed $element
     *
     * @return mixed
     */
    function process($element);


    /**
     * the attributes definition
     *
     * @return array
     */
    function attributes();

}