<?php

namespace Temple\Models\Plugins;


/**
 * Class Plugins
 *
 * @package Temple
 */
interface PluginInterface
{

    /**
     * @return integer
     */
    public function position();


    /**
     * @return string
     */
    public function getName();


    /**
     * @return bool
     */
    public function isFunction();


    /**
     * @return bool
     */
    public function isPreProcessor();


    /**
     * @return bool
     */
    public function isProcessor();


    /**
     * @return bool
     */
    public function isPostProcessor();


    /**
     * @return bool
     */
    public function isOutputProcessor();


}