<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;

/**
 * Class Containers
 *
 * TODO: multidiminesional positions
 *
 * @package Caramel\Services
 */
class Containers extends Service
{

    /** @var array $containers */
    private $containers;


    /**
     * adds a new container to the plugins configuration
     *
     * @param array|string $containers
     * @throws CaramelException
     */
    public function add($containers)
    {
        $this->containers = $this->config->get("plugin_containers");
        if (is_string($containers)) {
            $this->containers[] = $containers;
        } elseif (is_array($containers)) {
            foreach ($containers as $container) {
                $this->containers[] = $container;
            }
        } else {
            throw new CaramelException("Only arrays and strings are accepted");
        }

        $this->config->set("plugin_containers", $this->containers);
    }


    /**
     * removes a new container to the plugins configuration
     *
     * @param array|string $containers
     * @throws CaramelException
     */
    public function remove($containers)
    {
        $this->containers = $this->config->get("plugin_containers");
        if (is_string($containers)) {
            $temp = array_flip($this->containers);
            $temp = $temp[ $containers ];
            unset($this->containers[ $temp ]);
        } elseif (is_array($containers)) {
            foreach ($containers as $container) {
                $temp = array_flip($this->containers);
                $temp = $temp[ $container ];
                unset($this->containers[ $temp ]);
            }
        } else {
            throw new CaramelException("Only arrays and strings are accepted");
        }
        $this->config->set("plugin_containers", $this->containers);
    }


    /**
     * empties the plugin containers
     *
     * @return bool
     */
    public function flush()
    {
        $this->containers = array();

        return $this->config->set("plugin_containers", $this->containers);
    }


    /**
     * returns the plugin containers
     *
     * @return array
     */
    public function get()
    {
        return $this->config->get("plugin_containers");
    }
}