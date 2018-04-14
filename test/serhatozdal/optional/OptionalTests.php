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

    public function testOfNullableEqualsEmptyInstanceWhenValueIsNull()
    {
        $this->assertEquals(Optional::ofEmpty(), Optional::ofNullable(null));
    }

    public function testGetThrowsExceptionWhenValueIsNull()
    {
        try {
            Optional::ofNullable(null)->get();
            $this->fail("get method throws an exception when value is null!");
        } catch (\Exception $e) {
            $this->isInstanceOf(\InvalidArgumentException::class);
        }
    }

    public function testExecuteFunctionIfValuePresent()
    {
        $isPresent = false;
        Optional::of(5)->ifPresent(function () use (&$isPresent) { $isPresent = true; });
        $this->assertEquals(true, $isPresent);
    }

    public function testNotExecuteFunctionIfValueNotPresent()
    {
        $isPresent = false;
        Optional::ofEmpty()->ifPresent(function () use (&$isPresent) { $isPresent = true; });
        $this->assertEquals(false, $isPresent);
    }

    public function testMapWhenValueIsPresent()
    {
        $optional = Optional::of(5)->map(function ($a) { return $a * 2; });
        $this->assertEquals(10, $optional->get());
    }

    public function testFlatMapWhenValueIsPresent()
    {
        try {
            $optional = Optional::of(5)->flatMap(function ($a) { return $a * 2; });
            $this->assertEquals(10, $optional);
        } catch (\Exception $e) {
            $this->fail("exception not thrown when value is present!!");
        }
    }

    public function testFlatMapThrowsExceptionWhenFunctionReturnsNull()
    {
        try {
            Optional::of(5)->flatMap(function ($a) { return null; });
            $this->fail("Exception should thrown when function returns null!!");
        } catch (\Exception $e) {
            $this->isInstanceOf(\InvalidArgumentException::class);
        }
    }

    public function testOrElseGetValueWhenValueIsPresent()
    {
        $optional = Optional::of(5)->orElseGet(function () { return 10; });
        $this->assertEquals(5, $optional);
    }

    public function testOrElseGetValueWhenValueIsNotPresent()
    {
        $optional = Optional::ofEmpty()->orElseGet(function () { return 10; });
        $this->assertEquals(10, $optional);
    }
}