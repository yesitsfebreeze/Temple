<?php


namespace Caramel\Repositories;


use Caramel\Models\ServiceModel;

/**
 * Class Handler
 *
 * @package Caramel\Repositories
 */
class ServiceRepository
{


    /**
     * @param string          $name
     * @param ServiceModel|StorageRepository $instance
     */
    public function add($name, $instance)
    {
        if ($instance instanceof ServiceModel || $instance instanceof StorageRepository) {
            $this->$name = $instance;
        }
    }


    /**
     * @param string $name
     * @return null
     */
    public function get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return NULL;
    }
}