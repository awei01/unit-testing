<?php namespace UnitTesting\MockeryHelper;
use Mockery;
class MockeryTraitTest extends \PHPUnit_Framework_TestCase {

	function test_mockOnTestCase_ValidClass_ReturnsInstanceOfMockInterface()
	{
		$case = new FakeTest;
		$result = $case->mock('\UnitTesting\MockeryHelper\ToBeMockedStub');
		$this->assertInstanceOf('Mockery\MockInterface', $result);
	}
	function test_mockOnTestCase_ValidClass_ReturnsInstanceOfMockedClass()
	{
		$case = new FakeTest;
		$result = $case->mock('\UnitTesting\MockeryHelper\ToBeMockedStub');
		$this->assertInstanceOf('UnitTesting\MockeryHelper\ToBeMockedStub', $result);
	}
	function test_mockOnTestCase_ReturnsMock_CanAssertAgainstFunctionsOfMock()
	{
		$case = new FakeTest;
		$mock = $case->mock('UnitTesting\MockeryHelper\ToBeMockedStub');
		$parent = new DependentOnMockStub($mock);
		$mock->shouldReceive('doFoo')->once()->andReturn('bar');
		$result = $parent->foo();
		$this->assertEquals('bar', $result);
	}
	function test_spyOnTestCase_ValidClass_ReturnsInstanceOfMockInterface()
	{
		$case = new FakeTest;
		$result = $case->spy('UnitTesting\MockeryHelper\ToBeMockedStub');
		$this->assertInstanceOf('Mockery\MockInterface', $result);
	}
	function test_spyOnTestCase_ValidClass_ReturnsInstanceOfMockedClass()
	{
		$case = new FakeTest;
		$result = $case->spy('\UnitTesting\MockeryHelper\ToBeMockedStub');
		$this->assertInstanceOf('UnitTesting\MockeryHelper\ToBeMockedStub', $result);
	}
	function test_closeMocks_AfterMockGenerated_ShouldUnsetMockeryContainer()
	{
		$case = new FakeTest;
		$case->mock('foo');
		$case->closeMocks();
		$this->setExpectedException('LogicException', 'You have not declared any mocks yet');
		Mockery::self();
	}
	function test_spyOnTestCase_ReturnsMock_CanAssertAgainstMissingFunctionsOfMock()
	{
		$case = new FakeTest;
		$spy = $case->spy('UnitTesting\MockeryHelper\ToBeMockedStub');
		$parent = new DependentOnMockStub($spy);
		$result = $parent->foo();
		$this->assertNull($result);
	}

	function test_mockery_MethodNameAndArguments_ReturnsSameAsCallingStaticMethodWithArguments()
	{
		$case = new FakeTest;
		$result = $case->mockery('mock', 'StdClass');

		$this->assertInstanceOf('Mockery\MockInterface', $result);
	}
	/*
	*/
}

class FakeTest {
	use MockeryTrait;
	public function __call($method, $args)
	{
		return call_user_func_array(array($this, $method), $args);
	}
}
class ToBeMockedStub {
	public function doFoo()
	{
		return 'foo';
	}
}

class DependentOnMockStub {
	protected $dependency;
	public function __construct(ToBeMockedStub $dependency)
	{
		$this->dependency = $dependency;
	}
	public function foo()
	{
		return $this->dependency->doFoo();
	}
}
