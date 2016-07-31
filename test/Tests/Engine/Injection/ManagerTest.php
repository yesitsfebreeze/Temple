<?php


class TestDependencyInstance extends \Rite\Engine\Injection\Instance
{

    public function dependencies()
    {
        return array();
    }

}


class TestDependencyInstanceWithDependencies extends \Rite\Engine\Injection\Instance
{

    public function dependencies()
    {
        return array(
            "TestDependencyInstance" => "Test"
        );
    }

}


class TestDependencyInstanceWithDependenciesAndMemberVariable extends \Rite\Engine\Injection\Instance
{

    /** @var  TestDependencyInstance $Test */
    protected $Test;

    public function dependencies()
    {
        return array(
            "TestDependencyInstance" => "Test"
        );
    }

}


class DependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /** @var  \Rite\DependencyManager\DependencyContainer $container */
    private $container;


    public function testDependencyContainerCreation()
    {
        $this->container = new  \Rite\Engine\Injection\Manager();
    }


    public function testDependencyRegistration()
    {
        $this->testDependencyContainerCreation();
        $expected = new TestDependencyInstance();
        $result   = $this->container->registerDependency($expected);
        $this->assertEquals($expected, $result);
    }


    public function testDependencyRegistrationWithDependencies()
    {
        $this->testDependencyContainerCreation();
        $dependency = new TestDependencyInstance();
        $this->container->registerDependency($dependency);
        $expected = new TestDependencyInstanceWithDependenciesAndMemberVariable();
        $result   = $this->container->registerDependency($expected);
        $this->assertEquals($expected, $result);
    }


    public function testDependencyInstanceGetter()
    {
        $this->testDependencyContainerCreation();
        $expected = new TestDependencyInstance();
        $this->container->registerDependency($expected);
        $result = $this->container->getInstance("TestDependencyInstance");
        $this->assertEquals($expected, $result);
    }


    /**
     * @expectedException \Rite\Exception\Exception
     */
    public function testDependencyInstanceGetterException()
    {
        $this->testDependencyContainerCreation();
        $expected = new TestDependencyInstance();
        $result   = $this->container->getInstance("TestDependencyInstance");
        $this->assertEquals($expected, $result);
    }


    /**
     * @expectedException \Rite\Exception\Exception
     */
    public function testDependencyRegistrationWithDependenciesException()
    {
        $this->testDependencyContainerCreation();
        $expected = new TestDependencyInstanceWithDependencies();
        $this->container->registerDependency($expected);
    }


    /**
     * @expectedException \Rite\Exception\Exception
     */
    public function testDependencyRegistrationWithDependenciesMemberVariableException()
    {
        $this->testDependencyContainerCreation();
        $dependency = new TestDependencyInstance();
        $this->container->registerDependency($dependency);
        $expected = new TestDependencyInstanceWithDependencies();
        $this->container->registerDependency($expected);
    }


}