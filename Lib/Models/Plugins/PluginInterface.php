<?php

namespace Temple\Models\Plugins;


use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;


/**
 * Class Plugins
 *
 * @package Temple
 */
interface PluginInterface
{

    /**
     * @return int
     */
    function position();


    /**
     * @return bool
     */
    function isFunction();


    /**
     * @return array
     */
    function forTags();


}