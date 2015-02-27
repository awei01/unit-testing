<?php namespace UnitTesting\ClassSpy;

class WatchableTraitTest extends \PHPUnit_Framework_TestCase {

// getAllMethodCalls
	function test_getAllMethodCalls_NoParams_ReturnsEmptyArray()
	{
		$instance = new WatchableTraitTestStub();

		$result = $instance->getAllMethodCalls();

		$this->assertEquals(array(), $result);
	}
	function test_getAllMethodCalls_NoParamsWhenMethodCalledWithNoArguments_ReturnsArrayWithMethodAsKeyAndValueAsArrayContainingEmptyArray()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething();

		$result = $instance->getAllMethodCalls();

		$this->assertEquals(array('doSomething' => array(array())), $result);
	}
// getMethodCalls
	function test_getMethodCalls_MethodWhenNotCalled_ReturnsNull()
	{
		$instance = new WatchableTraitTestStub();

		$result = $instance->getMethodCalls('doSomething');

		$this->assertNull($result);
	}
	function test_getMethodCalls_MethodWhenCalledWithArguments_ReturnsArrayOfArrayOfArguments()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');

		$result = $instance->getMethodCalls('doSomething');

		$this->assertEquals(array(array('param1', 'param2')), $result);
	}
	function test_getMethodCalls_MethodWhenCalledMoreThanOnceWithArguments_ReturnsArrayOfArrayOfArguments()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getMethodCalls('doSomething');

		$this->assertEquals(array(array('param1', 'param2'), array('param3')), $result);
	}
	function test_getMethodCalls_MethodAnd0WhenCalledMoreThanOnceWithArguments_ReturnsArrayOfArrayOfArguments()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getMethodCalls('doSomething', 0);

		$this->assertEquals(array(array('param1', 'param2'), array('param3')), $result);
	}
	function test_getMethodCalls_MethodAnd1WhenCalledMoreThanOnceWithArguments_ReturnsArrayOfArgumentsForFirstCall()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getMethodCalls('doSomething', 1);

		$this->assertEquals(array('param1', 'param2'), $result);
	}
	function test_getMethodCalls_MethodAndNumberGreaterThanCallsWhenCalledMoreThanOnceWithArguments_ReturnsNull()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getMethodCalls('doSomething', 100);

		$this->assertNull($result);
	}
	function test_getMethodCalls_MethodAndLastWhenCalledMoreThanOnceWithArguments_ReturnsArrayOfLastArguments()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getMethodCalls('doSomething', 'last');

		$this->assertEquals(array('param3'), $result);
	}
// getLastMethodCall
	function test_getLastMethodCall_MethodWhenCalledMoreThanOnceWithArguments_ReturnsArrayOfLastArguments()
	{
		$instance = new WatchableTraitTestStub();
		$instance->doSomething('param1', 'param2');
		$instance->doSomething('param3');

		$result = $instance->getLastMethodCall('doSomething');

		$this->assertEquals(array('param3'), $result);
	}
// setMethodResult
	function test_setMethodResult_MethodAndResult_SetsMethodResult()
	{
		$instance = new WatchableTraitTestStub();
		$instance->setMethodResult('doSomething', 'result');

		$result = $instance->doSomething('param1', 'param2');

		$this->assertEquals('result', $result);
	}
	function test_setMethodResult_MethodAndClosure_ReturnsClosureResult()
	{
		$instance = new WatchableTraitTestStub();
		$instance->setMethodResult('doSomething', function($param1, $param2)
		{
			return $param1 . $param2;
		});

		$result = $instance->doSomething('param1', 'param2');

		$this->assertEquals('param1param2', $result);
	}
	function test_CallingWatchedMethod_WhenMethodResultNotSet_ReturnsNull()
	{
		$instance = new WatchableTraitTestStub();

		$result = $instance->doSomething('param1', 'param2');

		$this->assertNull($result);
	}
}

class WatchableTraitTestStub {
	use WatchableTrait;

	public function doSomething()
	{
		return $this->trackMethodCall();
	}

}
