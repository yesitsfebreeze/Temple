<?php

namespace Underware\Models;


use Underware\Instance;


/**
 * Class Plugins
 *
 * @package Underware
 */
interface PluginInterface
{

    /**
     * event dispatcher
     *
     * @param          $args
     * @param Instance $Instance
     *
     * @return mixed
     */
    function dispatch($args, Instance $Instance);


    /**
     * checking if the arguments are valid for this plugin
     *
     * @param mixed $args
     *
     * @return bool
     */
    function check($args);


    /**
     * the actual process method
     *
     * @param mixed $args
     *
     * @return mixed
     */
    function process($args);


}