<?php

namespace Caramel\Models;


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
         * the namsepace
         *
         * @var string
         */
        $this->set("namespace", "");

        /**
         * the template storage
         *
         * @var string
         */
        $this->set("template", "");

        /**
         * if we want to display the tags
         *
         * @var string
         */
        $this->set("nodes", array());

    }
}
