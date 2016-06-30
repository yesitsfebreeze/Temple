<?php

namespace Shift\Template;


use Shift\Exception\ShiftException;
use Shift\Utilities\FactoryBase;
use Shift\Utilities\Config;


/**
 * Class NodeFactory
 *
 * @package Contentmanager\Services
 */
class NodeFactory extends FactoryBase
{

    /** @var Config $Config */
    protected $Config;

    /** @inheritdoc */
    public function dependencies()
    {
        return $this->getDependencies();
    }

    /** @inheritdoc */
    public function create($class)
    {

        $class = $this->check($class);

        if (is_null($class)) {
            throw new ShiftException("Cant find the wanted Node Class!");
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
        $class = '\\Shift\\Models\\' . ucfirst($class) . "Node";

        if (class_exists($class)) {
            return $class;
        } else {
            return null;
        }
    }

}