<?php

namespace Caramel\Factories;
use Caramel\Services\ConfigService;

/**
 * Class configFactory
 * @package Contentmanager\Services
 */
class NodeFactory extends Factory
{


    /** @var  ConfigService $config */
    private $config;

    public function addConfig($config)
    {
        $this->config = $config;
    }

    /** @inheritdoc */
    public function create($class)
    {

        $class = $this->check($class);
        if ($class) {
            return new $class($this->config);
        }

        return null;
    }

    /** @inheritdoc */
    public function check($line)
    {

        $identifier = trim($line)[0];
        if ($identifier == "+") {
            $class = "function";
        } else {
            $class = "html";
        }

        $this->getClassName($class);
        $class = '\\Caramel\\Nodes\\' . ucfirst($class) . "Node";

        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }

}