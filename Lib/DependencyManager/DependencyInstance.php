<?php


namespace Underware\DependencyManager;


use Underware\Exception\Exception;
use Underware\Exception\ExceptionHandler;


abstract class DependencyInstance implements DependencyInterface
{
    
    /**
     * has to add an instance to the class
     *
     * @param string             $name
     * @param DependencyInstance $instance
     *
     * @throws Exception
     */
    public function setDependency($name, DependencyInstance $instance)
    {
        if (!property_exists($this, $name)) {
            new ExceptionHandler();
            throw new Exception("Dependency Management: Please register 'protected $$name'", get_class($this) . ".php");
        }

        $this->$name = $instance;
    }


    /**
     * must return an array of the namespaced class and its member variable name
     */
    public function getDependencies()
    {
        $dependencies = $this->getDependencyFile();
        $name         = $this->cleanClassNamespace($this);
        if (!isset($dependencies[ $name ])) {
            new ExceptionHandler();
            throw new Exception("Dependency Management: Please register the dependency, '" . $name . "'!");
        }

        return $dependencies[ $name ];
    }


    private function getDependencyFile()
    {
        $dependencies = false;
        $file         = __DIR__ . "/dependencies.php";
        /** @noinspection PhpIncludeInspection */
        require $file;

        if (!is_array($dependencies)) {
            throw new Exception("You should set the array '\$dependencies' in:", $file);
        };

        return $dependencies;
    }


    /**
     * returns the name of a Class
     *
     * @param DependencyInterface $instance
     *
     * @return mixed
     */
    private function cleanClassNamespace(DependencyInterface $instance)
    {
        $name = get_class($instance);
        $name = str_replace("\\", "/", $name);
        $rootNameSpace = explode("\\",__NAMESPACE__);
        $rootNameSpace = $rootNameSpace[0];
        $name = str_replace($rootNameSpace . "/", "", $name);

        return $name;
    }
}