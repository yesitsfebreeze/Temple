<?php

namespace Temple\Template;


use Temple\Exception\TempleException;
use Temple\Utilities\FactoryBase;
use Temple\Utilities\Config;


/**
 * Class NodeFactory
 *
 * @package Contentmanager\Services
 */
class NodeFactory extends FactoryBase
{

    /** @var Config $Config */
    protected $Config;

    public function dependencies()
    {
        return array(
            "Utilities/Config" => "Config"
        );
    }



    /** @inheritdoc */
    public function create($class)
    {

        $class = $this->check($class);

        if (is_null($class)) {
            throw new TempleException("Cant find the wanted Node Class!");
        }

        return new $class($this->Config);

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
        $class = '\\Temple\\Models\\' . ucfirst($class) . "Node";

        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }

}