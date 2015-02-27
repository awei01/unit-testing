<?php namespace UnitTesting\FunctionSpy;

class RegistryTest extends \PHPUnit_Framework_TestCase {

	protected function makeRegistry()
	{
		return new Registry();
	}

// setMethodResult
	function test_setMethodResult_MethodAndResult_SetsResultOnMethod()
	{
		$registry = $this->makeRegistry();
		$registry->setMethodResult('foo', 'foo result');
		$method = $registry->getSpiedMethod('foo');

		$result = $method->getResult();

		$this->assertEquals('foo result', $result);
	}
// spyMethodCall
	function test_spyMethodCall_MethodAndArray_SetsMethodsWithMethodAsKeyAndValueAsInstanceOfMethod()
	{
		$registry = $this->makeRegistry();
		$registry->spyMethodCall('foo', array('foo1', 'foo2'));

		$result = $registry->getSpiedMethod('foo');

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Method', $result);
	}
	function test_spyMethodCall_MethodAndArray_SetsArgumentsOnMethod()
	{
		$registry = $this->makeRegistry();
		$registry->spyMethodCall('foo', array('foo1', 'foo2'));
		$method = $registry->getSpiedMethod('foo');

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo1', 'foo2')), $result);
	}
	function test_spyMethodCall_MethodAndArrayCalledMoreThanOnce_AddsArgumentsOnMethod()
	{
		$registry = $this->makeRegistry();
		$registry->spyMethodCall('foo', array('foo1', 'foo2'));
		$registry->spyMethodCall('foo', array('foo3', 'foo4'));
		$method = $registry->getSpiedMethod('foo');

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo1', 'foo2'), array('foo3', 'foo4')), $result);
	}
	function test_spyMethodCall_WhenResultNotSet_ReturnsNull()
	{
		$registry = $this->makeRegistry();
		$result = $registry->spyMethodCall('foo', array('foo1', 'foo2'));

		$this->assertNull($result);
	}
	function test_spyMethodCall_WhenResultIsSet_ReturnsResult()
	{
		$registry = $this->makeRegistry();
		$registry->setMethodResult('foo', 'foo result');

		$result = $registry->spyMethodCall('foo', array('foo1', 'foo2'));

		$this->assertEquals('foo result', $result);
	}

// flushSpiedMethods
	function test_flushSpiedMethods_WhenMethodsSet_SetsMethodsAsEmptyArray()
	{
		$registry = $this->makeRegistry();
		$registry->spyMethodCall('foo', array('foo1', 'foo2'));
		$registry->flushSpiedMethods();

		$result = $registry->getAllSpiedMethods();

		$this->assertEquals(array(), $result);
	}

// handling overloaded calls
	function test_overloadedCall_WithParams_AddsMethodCallWithParams()
	{
		$registry = $this->makeRegistry();
		$registry->foo('foo1', 'foo2');
		$method = $registry->getSpiedMethod('foo');

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo1', 'foo2')), $result);
	}
	function test_overloadedCall_WhenResultIsSet_ReturnsResult()
	{
		$registry = $this->makeRegistry();
		$registry->setMethodResult('foo', 'foo result');

		$result = $registry->foo('foo1', 'foo2');

		$this->assertEquals('foo result', $result);
	}

/*
*/
}
