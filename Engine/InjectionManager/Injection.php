<?php


namespace Underware\Engine\InjectionManager;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Exception\ExceptionHandler;


abstract class Injection implements InjectionInterface
{

    /** @var  InjectionManager $InjectionManager */
    protected $InjectionManager;


    /**
     * by default a injection has no dependencies
     *
     * @return array
     */
    public function dependencies()
    {
        return array();
    }


    /**
     * has to add an instance to the class
     *
     * @param string    $name
     * @param Injection $instance
     *
     * @throws Exception
     */
    public function setDependency($name, Injection $instance)
    {
        if (!property_exists($this, $name)) {
            new ExceptionHandler();
            throw new Exception(1,"Dependency Management: Please register %protected $" . $name . "%", get_class($this) . ".php");
        }

        $this->$name = $instance;
    }


    /**
     * @param InjectionManager $InjectionManager
     *
     * @return mixed
     */
    function setInjectionManager(InjectionManager $InjectionManager)
    {
        $this->InjectionManager = $InjectionManager;
    }

}