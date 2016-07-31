<?php


class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Rite\Exception\Exception
     */
    public function testException()
    {
        throw new \Rite\Engine\Exception\Exception("test", "myfile", "myline");
    }


    public function testExceptionMessage()
    {
        $expected = "test";
        $e        = new \Rite\Engine\Exception\Exception($expected, "myfile", "myline");
        $result   = $e->getMessage();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionFile()
    {
        $expected = "test";
        $e        = new \Rite\Engine\Exception\Exception("test", $expected, "myline");
        $result   = $e->getCustomFile();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionLine()
    {
        $expected = "myline";
        $e        = new \Rite\Engine\Exception\Exception("test", "myfile", $expected);
        $result   = $e->getCustomLine();
        $this->assertEquals($expected, $result);
    }

}