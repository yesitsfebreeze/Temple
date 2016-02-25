<?php

namespace Caramel;


/**
 * Class Dom
 *
 * @package Caramel
 */
class Dom extends Storage
{

    /**
     * Dom constructor.
     */
    public function __construct()
    {

        /**
         * the tag name
         *
         * @var string
         */
        $this->set("file", "");

        /**
         * if we want to display the tags
         *
         * @var string
         */
        $this->set("nodes", array());

    }
}
