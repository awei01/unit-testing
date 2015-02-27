<?php namespace UnitTesting\FunctionSpy;

class RegistryTest extends \PHPUnit_Framework_TestCase {

	protected function makeRegistry()
	{
		return new Registry();
	}

// setFunctionResult
	function test_setFunctionResult_MethodAndResult_SetsResultOnMethod()
	{
		$registry = $this->makeRegistry();
		$registry->setFunctionResult('foo', 'foo result');
		$recorder = $registry->getRecorder('foo');

		$result = $recorder->getResult();

		$this->assertEquals('foo result', $result);
	}
// handling overloaded calls
	function test_overloadedCall_MethodAndArray_SetsMethodsWithMethodAsKeyAndValueAsInstanceOfRecorder()
	{
		$registry = $this->makeRegistry();
		$registry->foo('param1', 'param2');

		$result = $registry->getRecorder('foo');

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Recorder', $result);
	}
	function test_overloadedCall_MethodAndArray_SetsArgumentsOnRecorder()
	{
		$registry = $this->makeRegistry();
		$registry->foo('param1', 'param2');
		$recorder = $registry->getRecorder('foo');

		$result = $recorder->getCalls();

		$this->assertEquals(array(array('param1', 'param2')), $result);
	}
	function test_overloadedCall_MethodAndArrayCalledMoreThanOnce_AddsArgumentsOnMethod()
	{
		$registry = $this->makeRegistry();
		$registry->foo('param1', 'param2');
		$registry->foo('param3', 'param4');
		$recorder = $registry->getRecorder('foo');

		$result = $recorder->getCalls();

		$this->assertEquals(array(array('param1', 'param2'), array('param3', 'param4')), $result);
	}
	function test_overloadedCall_WhenResultNotSet_ReturnsNull()
	{
		$registry = $this->makeRegistry();
		$result = $registry->foo('param1', 'param2');

		$this->assertNull($result);
	}
	function test_overloadedCall_WhenResultIsSet_ReturnsResult()
	{
		$registry = $this->makeRegistry();
		$registry->setFunctionResult('foo', 'foo result');

		$result = $registry->foo('param1', 'param2');

		$this->assertEquals('foo result', $result);
	}

// flushRecorders
	function test_flushRecorders_WhenMethodsSet_SetsMethodsAsEmptyArray()
	{
		$registry = $this->makeRegistry();
		$registry->foo('param1', 'param2');
		$registry->flushRecorders();

		$result = $registry->getRecorders();

		$this->assertEquals(array(), $result);
	}

// array access
	function test_offsetExists_SpySet_ReturnsTrue()
	{
		$registry = $this->makeRegistry();
		$registry->foo();

		$result = isset($registry['foo']);

		$this->assertTrue($result);
	}
	function test_offsetExists_SpyNotSet_ReturnsFalse()
	{
		$registry = $this->makeRegistry();

		$result = isset($registry['foo']);

		$this->assertFalse($result);
	}
	function test_offsetGet_SpySet_ReturnsSpy()
	{
		$registry = $this->makeRegistry();
		$registry->foo();
		$recorder = $registry->getRecorder('foo');

		$result = $registry['foo'];

		$this->assertEquals($recorder, $result);
	}
	function test_offsetGet_SpyNotSet_ReturnsInstanceOfRecorder()
	{
		$registry = $this->makeRegistry();

		$result = $registry['foo'];

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Recorder', $result);
	}
	function test_offsetGet_CalledTwice_ReturnsSameInstanceOfRecorder()
	{
		$registry = $this->makeRegistry();
		$recorder = $registry['foo'];

		$result = $registry['foo'];

		$this->assertEquals($recorder, $result);
	}
	function test_offsetSet_KeyAndValue_SetsResultOnRecorder()
	{
		$registry = $this->makeRegistry();
		$registry['foo'] = 'bar';

		$result = $registry->getRecorder('foo')->getResult();

		$this->assertEquals('bar', $result);
	}
	function test_offsetUnset_KeyAndValue_ThrowsOverflowException()
	{
		$registry = $this->makeRegistry();

		$this->setExpectedException('OverflowException', 'Cannot unset property');

		unset($registry['foo']);
	}

/*
*/
}
