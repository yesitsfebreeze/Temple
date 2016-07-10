<?php

namespace Underware\Models\Plugins;


/**
 * Class Plugins
 *
 * @package Underware
 */
interface PluginInterface
{

    
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