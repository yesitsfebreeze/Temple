<?php

namespace Temple\Factories;


use Temple\Services\ConfigService;


/**
 * Class configFactory
 *
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

        return $this->getClass($class);
    }


    /**
     * @param $class
     * @return null|string
     * @throws \Exception
     */
    private function getClass($class)
    {
        $this->getClassName($class);
        $class = '\\Temple\\Nodes\\' . ucfirst($class) . "Node";

        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }

}