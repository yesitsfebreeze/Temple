<?php


class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Temple\Exception\Exception
     */
    public function testException()
    {
        throw new \Temple\Engine\Exception\Exception("test", "myfile", "myline");
    }


    public function testExceptionMessage()
    {
        $expected = "test";
        $e        = new \Temple\Engine\Exception\Exception($expected, "myfile", "myline");
        $result   = $e->getMessage();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionFile()
    {
        $expected = "test";
        $e        = new \Temple\Engine\Exception\Exception("test", $expected, "myline");
        $result   = $e->getCustomFile();
        $this->assertEquals($expected, $result);
    }


    public function testExceptionLine()
    {
        $expected = "myline";
        $e        = new \Temple\Engine\Exception\Exception("test", "myfile", $expected);
        $result   = $e->getCustomLine();
        $this->assertEquals($expected, $result);
    }

}