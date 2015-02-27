<?php namespace UnitTesting\FunctionSpy;

class SpyTest extends \PHPUnit_Framework_TestCase {
	protected function tearDown()
	{
		Spy::flushSpiedMethods();
	}

	function test_instance_NoParams_ReturnsInstanceOfRegistry()
	{
		$result = Spy::instance();

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Registry', $result);
	}
	function test_instance_MultipleCalls_ReturnsSameInstanceOfRegistry()
	{
		$first = Spy::instance();
		$second = Spy::instance();

		$result = $first === $second;

		$this->assertTrue($result);
	}

// handling overloaded calls
	function test_overloadedCall_WithParams_AddsMethodCallWithParams()
	{
		Spy::foo('foo1', 'foo2');
		$method = Spy::getSpiedMethod('foo');

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo1', 'foo2')), $result);
	}
	function test_overloadedCall_WhenCalledFromExternalMethod_AddsMethodCallWithEmtpyArgumentList()
	{
		spyTestStubFunction();
		$method = Spy::getSpiedMethod('spyTestStubFunction');

		$result = $method->getCalls();

		$this->assertEquals(array(array()), $result);
	}
	function test_overloadedCall_WhenCalledFromExternalMethodWithParams_AddsMetehodCallWithArguments()
	{
		spyTestStubFunction('foo1', 'foo2');
		$method = Spy::getSpiedMethod('spyTestStubFunction');

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo1', 'foo2')), $result);
	}
	function test_overloadedCall_WhenResultSet_ReturnsResult()
	{
		Spy::setMethodResult('spyTestStubFunction', 'result');

		$result = spyTestStubFunction('foo1', 'foo2');

		$this->assertEquals('result', $result);
	}

/*
*/
}

function spyTestStubFunction()
{
	return Spy::spyTestStubFunction();
}
