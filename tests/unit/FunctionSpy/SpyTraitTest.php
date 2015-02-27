<?php namespace UnitTesting\FunctionSpy;
use Mockery as m;
class SpyTraitTest extends \PHPUnit_Framework_TestCase {
	public function tearDown()
	{
		m::close();
	}
	protected function makeTestCase()
	{
		return new TestCaseUsingTraitStub();
	}

	function test_initSpy_NoParams_SetsSpyOnTestCaseAsInstanceOfRegistry()
	{
		$test = $this->makeTestCase();
		$test->initSpy();

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Registry', $test->getRecorder());
	}
	protected function fakeRegistry()
	{
		return m::mock('UnitTesting\FunctionSpy\Registry');
	}
	function test_flushSpy_WhenSpySet_CallsFlushRecordersOnSpyWithNoArgs()
	{
		$test = $this->makeTestCase();
		$test->setRecorder($mockSpy = $this->fakeRegistry());

		$mockSpy->shouldReceive('flushRecorders')->once()->withNoArgs();

		$test->flushSpy();
	}


// assertFunctionNotCalled
	protected function fakeTestCaseWithFakeSpy()
	{
		$test = m::mock('UnitTesting\FunctionSpy\TestCaseUsingTraitStub[fail]');
		$test->setRecorder($this->fakeRegistry());
		return $test;
	}
	function test_assertFunctionNotCalled_Name_CallsGetRecorderOnSpyWithName()
	{
		$test = $this->fakeTestCaseWithFakeSpy();

		$test->getRecorder()->shouldReceive('getRecorder')->once()->with('someFunction');

		$test->assertFunctionNotCalled('someFunction');
	}
	function test_assertFunctionNotCalled_WhenGetRecorderOnSpyReturnsNull_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn(null);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalled('someFunction');
	}
	protected function mockRecorder()
	{
		return m::mock('UnitTesting\FunctionSpy\Recorder');
	}
	function test_assertFunctionNotCalled_WhenGetRecorderOnSpyReturnsMethod_CallsWasCalledOnMethod()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->getRecorder()->shouldReceive('getRecorder')->andReturn($mockRecorder = $this->mockRecorder());

		$mockRecorder->shouldReceive('wasCalled')->once()->withNoArgs();

		$test->assertFunctionNotCalled('someFunction');
	}
	function test_assertFunctionNotCalled_WhenWasCalledOnMethodReturnsFalse_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalled')->andReturn(false);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalled('someFunction');
	}
	function test_assertFunctionNotCalled_WhenWasCalledOnMethodReturnsTrue_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalled')->andReturn(true);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] not to be called, but it was called.');

		$mockTest->assertFunctionNotCalled('someFunction');
	}
	function test_assertFunctionNotCalled_MethodAndArgumentsPassed_ThrowsInvalidArgumentError()
	{
		$test = $this->fakeTestCaseWithFakeSpy();

		$this->setExpectedException('InvalidArgumentException', '@assertFunctionNotCalled() expects only a method parameter. Did you mean to use @assertFunctionNotCalledWith()?');

		$test->assertFunctionNotCalled('someFunction', array('foo'));
	}

// assertFunctionNotCalledWith
	function test_assertFunctionNotCalledWith_NameAndArrayOfParams_CallsGetRecorderOnSpyWithMethod()
	{
		$test = $this->fakeTestCaseWithFakeSpy();

		$test->getRecorder()->shouldReceive('getRecorder')->once()->with('someFunction');

		$test->assertFunctionNotCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_WhenGetRecorderOnSpyReturnsNull_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn(null);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_MethodAndArgsWhenGetRecorderOnSpyReturnsMethod_CallsWasCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->getRecorder()->shouldReceive('getRecorder')->andReturn($mockRecorder = $this->mockRecorder());

		$mockRecorder->shouldReceive('wasCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionNotCalledWith('someFunction', $args);
	}
	function test_assertFunctionNotCalledWith_WhenWasCalledWithOnMethodReturnsFalse_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_WhenWasCalledWithOnMethodReturnsTrue_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] not to be called with [foo, bar].');

		$mockTest->assertFunctionNotCalledWith('someFunction', array('foo', 'bar'));
	}

// assertFunctionCalledWith
	function test_assertFunctionCalledWith_NameAndArrayOfParams_CallsGetOnSpyWithMethod()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->shouldReceive('fail');

		$test->getRecorder()->shouldReceive('getRecorder')->once()->with('someFunction');

		$test->assertFunctionCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenGetRecorderOnSpyReturnsNull_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn(null);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] to be called with [foo, bar].');

		$mockTest->assertFunctionCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_MethodAndArgsWhenGetRecorderOnSpyReturnsMethod_CallsWasCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->shouldReceive('fail');
		$test->getRecorder()->shouldReceive('getRecorder')->andReturn($mockRecorder = $this->mockRecorder());

		$mockRecorder->shouldReceive('wasCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionCalledWith('someFunction', $args);
	}
	function test_assertFunctionCalledWith_WhenWasCalledWithOnMethodReturnsTrue_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenWasCalledWithOnMethodReturnsFalse_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] to be called with [foo, bar].');

		$mockTest->assertFunctionCalledWith('someFunction', array('foo', 'bar'));
	}

// assertFunctionLastCalledWith
	function test_assertFunctionLastCalledWith_NameAndArrayOfParams_CallsGetRecorderOnSpyWithName()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->shouldReceive('fail');

		$test->getRecorder()->shouldReceive('getRecorder')->once()->with('someFunction');

		$test->assertFunctionLastCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_WhenGetRecorderOnSpyReturnsNull_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn(null);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] to be called, but it was never called.');

		$mockTest->assertFunctionLastCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_MethodAndArgsWhenGetRecorderOnSpyReturnsMethod_CallsWasLastCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeSpy();
		$test->shouldReceive('fail');
		$test->getRecorder()->shouldReceive('getRecorder')->andReturn($mockRecorder = $this->mockRecorder());

		$mockRecorder->shouldReceive('wasLastCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionLastCalledWith('someFunction', $args);
	}
	function test_assertFunctionLastCalledWith_WasLastCalledWithOnMethodReturnsFalse_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasLastCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someFunction] last called with [foo, bar].');

		$mockTest->assertFunctionLastCalledWith('someFunction', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_WasLastCalledWithOnMethodReturnsTrue_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeSpy();
		$mockTest->getRecorder()->shouldReceive('getRecorder')->andReturn($stubRecorder = $this->mockRecorder());
		$stubRecorder->shouldReceive('wasLastCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionLastCalledWith('someFunction', array('foo', 'bar'));
	}
/*
*/
}

class TestCaseUsingTraitStub {
	use SpyTrait {
		initSpy as traitInitSpy;
		flushSpy as traitFlushSpy;
	}

	public function initSpy()
	{
		$this->traitInitSpy();
	}

	public function flushSpy()
	{
		$this->traitFlushSpy();
	}

	public function getRecorder()
	{
		return $this->spy;
	}

	public function setRecorder($spy)
	{
		$this->spy = $spy;
	}
}
