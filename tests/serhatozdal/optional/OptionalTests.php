<?php
namespace serhatozdal\optional;

/**
 * @author serhatozdal
 */
class OptionalTests extends \PHPUnit_Framework_TestCase
{
    public function testOfEmptyEqualsEmptyInstance()
    {
        $this->assertFalse(Optional::ofEmpty()->isPresent());
    }

    public function testOrElseWhenValuePresentThenValue()
    {
        $optional = Optional::of("value");
        $this->assertEquals("value", $optional->orElse(""));
    }

    public function testOrElseWhenValueNotPresentThenEmpty()
    {
        $optional = Optional::ofEmpty();
        $this->assertEquals("", $optional->orElse(""));
    }

    public function testOrElseThrowWhenValuePresentThenValue()
    {
        try {
            $optional = Optional::of("value")->orElseThrow(function () { throw new \InvalidArgumentException(); });
            $this->assertEquals("value", $optional);
        } catch (\InvalidArgumentException $e) {
            $this->fail("test is fail, exception not thrown when value is present");
        }
    }

    public function testOrElseThrowWhenValueNotPresentThenThrowException()
    {
        $invalidException = new \InvalidArgumentException();
        try {
            Optional::ofEmpty()->orElseThrow(function () use ($invalidException) { throw $invalidException; });
            $this->fail("test is fail, exception should thrown when value is not present");
        } catch (\InvalidArgumentException $e) {
            $this->assertSame($invalidException, $e);
        }
    }

    public function testFilterIsNotExecutedWhenValueIsNotPresent()
    {
        $optional = Optional::ofEmpty()->filter(function ($a) { return (int) $a; });
        $this->assertEquals(Optional::ofEmpty(), $optional);
    }

    public function testFilterIsExecutedWhenValueIsPresent()
    {
        $optional = Optional::of("value")->filter(function ($a) { return is_int($a); });
        $this->assertEquals(Optional::ofEmpty(), $optional);
    }
}