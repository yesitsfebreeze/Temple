<?php

namespace Caramel\Models;

// todoo: add local variable space
// todoo: add find method
use Caramel\Repositories\StorageRepository;

/**
 * Class DomModel
 *
 * @package Caramel
 */
class DomModel extends StorageRepository
{

    /**
     * DomModel constructor.
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
