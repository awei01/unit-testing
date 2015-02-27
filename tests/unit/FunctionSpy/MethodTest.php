<?php namespace UnitTesting\FunctionSpy;

class MethodTest extends \PHPUnit_Framework_TestCase {

	function test_construct_NoParams_HasEmptyArguments()
	{
		$method = new Method();

		$result = $method->getCalls();

		$this->assertEquals(array(), $result);
	}

	function test_addCall_Array_AddsArrayToArgumentsArray()
	{
		$method = new Method();
		$method->addCall(array('foo', 'bar'));

		$result = $method->getCalls();

		$this->assertEquals(array(array('foo', 'bar')), $result);
	}

	function test_addCall_Array_ReturnsSelf()
	{
		$method = new Method();

		$result = $method->addCall(array('foo', 'bar'));

		$this->assertEquals($method, $result);
	}

	function test_wasCalled_WhenArgumentsNotSet_ReturnsFalse()
	{
		$method = new Method();

		$result = $method->wasCalled();

		$this->assertFalse($result);
	}
	function test_wasCalled_WhenArgumentsSet_ReturnsTrue()
	{
		$method = new Method();
		$method->addCall(array('foo', 'bar'));

		$result = $method->wasCalled();

		$this->assertTrue($result);
	}

	function test_wasCalledWith_WhenArgumentsDontMatch_ReturnsFalse()
	{
		$method = new Method();
		$method->addCall(array('foo1', 'foo2'));

		$result = $method->wasCalledWith(array('bar1', 'bar2'));

		$this->assertFalse($result);
	}
	function test_wasCalledWith_WhenArgumentsMatch_ReturnsTrue()
	{
		$method = new Method();
		$method->addCall(array('foo1', 'foo2'));

		$result = $method->wasCalledWith(array('foo1', 'foo2'));

		$this->assertTrue($result);
	}

	function test_wasLastCalledWith_WhenArgumentsDontMatch_ReturnsFalse()
	{
		$method = new Method();
		$method->addCall(array('foo1', 'foo2'));

		$result = $method->wasLastCalledWith(array('bar1', 'bar2'));

		$this->assertFalse($result);
	}
	function test_wasLastCalledWith_WhenArgumentsMatch_ReturnsTrue()
	{
		$method = new Method();
		$method->addCall(array('foo1', 'foo2'));

		$result = $method->wasLastCalledWith(array('foo1', 'foo2'));

		$this->assertTrue($result);
	}

	function test_getResult_NoParams_ReturnsNull()
	{
		$method = new Method();

		$result = $method->getResult();

		$this->assertNull($result);
	}
	function test_setResult_Value_SetsResult()
	{
		$method = new Method();
		$method->setResult('result');

		$result = $method->getResult();

		$this->assertEquals('result', $result);
	}

}
