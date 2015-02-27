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

		$this->assertInstanceOf('UnitTesting\FunctionSpy\Registry', $test->getSpy());
	}
	function test_flushSpy_WhenSpySet_CallsFlushSpiedMethodsOnSpyWithNoArgs()
	{
		$test = $this->makeTestCase();
		$test->setSpy($mockSpy = $this->fakeRegistry());

		$mockSpy->shouldReceive('flushSpiedMethods')->once()->withNoArgs();

		$test->flushSpy();
	}
	protected function fakeRegistry()
	{
		return m::mock('UnitTesting\FunctionSpy\Registry');
	}

	protected function fakeTestCaseWithFakeTracker()
	{
		$test = m::spy('UnitTesting\FunctionSpy\TestCaseUsingTraitStub[pass,fail]');
		$test->setSpy($this->fakeRegistry());
		return $test;
	}

// assertFunctionNotCalled
	function test_assertFunctionNotCalled_Name_CallsGetSpiedMethodOnSpyWithName()
	{
		$test = $this->fakeTestCaseWithFakeTracker();

		$test->getSpy()->shouldReceive('getSpiedMethod')->once()->with('someMethod');

		$test->assertFunctionNotCalled('someMethod');
	}
	function test_assertFunctionNotCalled_WhenGetSpiedMethodOnSpyReturnsNull_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn(null);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalled('someMethod');
	}
	protected function mockMethod()
	{
		return m::spy('UnitTesting\FunctionSpy\Method');
	}
	function test_assertFunctionNotCalled_WhenGetSpiedMethodOnSpyReturnsMethod_CallsWasCalledOnMethod()
	{
		$test = $this->fakeTestCaseWithFakeTracker();
		$test->getSpy()->shouldReceive('getSpiedMethod')->andReturn($mockMethod = $this->mockMethod());

		$mockMethod->shouldReceive('wasCalled')->once()->withNoArgs();

		$test->assertFunctionNotCalled('someMethod');
	}
	function test_assertFunctionNotCalled_WhenWasCalledOnMethodReturnsFalse_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalled')->andReturn(false);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalled('someMethod');
	}
	function test_assertFunctionNotCalled_WhenWasCalledOnMethodReturnsTrue_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalled')->andReturn(true);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] not to be called, but it was called.');

		$mockTest->assertFunctionNotCalled('someMethod');
	}
	function test_assertFunctionNotCalled_MethodAndArgumentsPassed_ThrowsInvalidArgumentError()
	{
		$test = $this->fakeTestCaseWithFakeTracker();

		$this->setExpectedException('InvalidArgumentException', '@assertFunctionNotCalled() expects only a method parameter. Did you mean to use @assertFunctionNotCalledWith()?');

		$test->assertFunctionNotCalled('someMethod', array('foo'));
	}

// assertFunctionNotCalledWith
	function test_assertFunctionNotCalledWith_NameAndArrayOfParams_CallsGetSpiedMethodOnSpyWithMethod()
	{
		$test = $this->fakeTestCaseWithFakeTracker();

		$test->getSpy()->shouldReceive('getSpiedMethod')->once()->with('someMethod');

		$test->assertFunctionNotCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_WhenGetSpiedMethodOnSpyReturnsNull_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn(null);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_MethodAndArgsWhenGetSpiedMethodOnSpyReturnsMethod_CallsWasCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeTracker();
		$test->getSpy()->shouldReceive('getSpiedMethod')->andReturn($mockMethod = $this->mockMethod());

		$mockMethod->shouldReceive('wasCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionNotCalledWith('someMethod', $args);
	}
	function test_assertFunctionNotCalledWith_WhenWasCalledWithOnMethodReturnsFalse_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionNotCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionNotCalledWith_WhenWasCalledWithOnMethodReturnsTrue_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] not to be called with [foo, bar].');

		$mockTest->assertFunctionNotCalledWith('someMethod', array('foo', 'bar'));
	}

// assertFunctionCalledWith
	function test_assertFunctionCalledWith_NameAndArrayOfParams_CallsGetOnSpyWithMethod()
	{
		$test = $this->fakeTestCaseWithFakeTracker();

		$test->getSpy()->shouldReceive('getSpiedMethod')->once()->with('someMethod');

		$test->assertFunctionCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenGetSpiedMethodOnSpyReturnsNull_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn(null);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] to be called with [foo, bar].');

		$mockTest->assertFunctionCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_MethodAndArgsWhenGetSpiedMethodOnSpyReturnsMethod_CallsWasCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeTracker();
		$test->getSpy()->shouldReceive('getSpiedMethod')->andReturn($mockMethod = $this->mockMethod());

		$mockMethod->shouldReceive('wasCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionCalledWith('someMethod', $args);
	}
	function test_assertFunctionCalledWith_WhenWasCalledWithOnMethodReturnsTrue_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenWasCalledWithOnMethodReturnsFalse_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] to be called with [foo, bar].');

		$mockTest->assertFunctionCalledWith('someMethod', array('foo', 'bar'));
	}

// assertFunctionLastCalledWith
	function test_assertFunctionLastCalledWith_NameAndArrayOfParams_CallsGetSpiedMethodOnSpyWithName()
	{
		$test = $this->fakeTestCaseWithFakeTracker();

		$test->getSpy()->shouldReceive('getSpiedMethod')->once()->with('someMethod');

		$test->assertFunctionLastCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_WhenGetSpiedMethodOnSpyReturnsNull_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn(null);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] to be called, but it was never called.');

		$mockTest->assertFunctionLastCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_MethodAndArgsWhenGetSpiedMethodOnSpyReturnsMethod_CallsWasLastCalledWithOnMethodWithArgs()
	{
		$test = $this->fakeTestCaseWithFakeTracker();
		$test->getSpy()->shouldReceive('getSpiedMethod')->andReturn($mockMethod = $this->mockMethod());

		$mockMethod->shouldReceive('wasLastCalledWith')->once()->with($args = array('foo', 'bar'));

		$test->assertFunctionLastCalledWith('someMethod', $args);
	}
	function test_assertFunctionLastCalledWith_WasLastCalledWithOnMethodReturnsFalse_CallsFailWithMessage()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasLastCalledWith')->andReturn(false);

		$mockTest->shouldReceive('fail')->once()->with('Expected [someMethod] last called with [foo, bar].');

		$mockTest->assertFunctionLastCalledWith('someMethod', array('foo', 'bar'));
	}
	function test_assertFunctionLastCalledWith_WasLastCalledWithOnMethodReturnsTrue_NeverCallsFail()
	{
		$mockTest = $this->fakeTestCaseWithFakeTracker();
		$mockTest->getSpy()->shouldReceive('getSpiedMethod')->andReturn($stubMethod = $this->mockMethod());
		$stubMethod->shouldReceive('wasLastCalledWith')->andReturn(true);

		$mockTest->shouldReceive('fail')->never();

		$mockTest->assertFunctionLastCalledWith('someMethod', array('foo', 'bar'));
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

	public function getSpy()
	{
		return $this->spy;
	}

	public function setSpy($spy)
	{
		$this->spy = $spy;
	}
}
