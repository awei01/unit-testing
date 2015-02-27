<?php namespace UnitTesting\FunctionSpy;

class SpyTraitIntegrationTest extends \PHPUnit_Framework_TestCase {
	use SpyTrait;
	protected function setUp()
	{
		$this->initSpy();
	}
	protected function tearDown()
	{
		$this->flushSpy();
	}

	function test_callingSpyFromExternalFunction_ProperlyRecordsMethodAndCalls()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertEquals(array(array('foo', 'bar')), $this->spy->getSpiedMethod('spyIntegrationTestStubFunction')->getCalls());
	}
	function test_callingSpiedFunctionFromClassMethod_ProperlyRecordsMethodAndCalls()
	{
		$myclass = new SpyTraitFakeClassStub();
		$myclass->callSpiedMethod('foo', 'bar');

		$this->assertEquals(array(array('foo', 'bar')), $this->spy->getSpiedMethod('spyIntegrationTestStubFunction')->getCalls());
	}
	function test_setMethodResult_ReturnsResult()
	{
		Spy::setMethodResult('spyIntegrationTestStubFunction', 'foo');
		$this->assertEquals('foo', spyIntegrationTestStubFunction('foo', 'bar'));
	}
	function test_subesquentTestImplicitlyFlushesCallsOnSpy()
	{
		$this->assertEquals(array(), $this->spy->getAllSpiedMethods());
	}
	function test_assertFunctionNotCalled_WhenMethodNotCalled_Passes()
	{
		$this->assertFunctionNotCalled('spyIntegrationTestStubFunction');
	}
	function test_assertFunctionNotCalled_WhenMethodIsCalled_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionNotCalled('spyIntegrationTestStubFunction');
	}

	function test_assertFunctionNotCalledWith_WhenMethodNotCalledWithArguments_Passes()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertFunctionNotCalledWith('spyIntegrationTestStubFunction', array('bar', 'baz'));
	}
	function test_assertFunctionNotCalledWith_WhenMethodIsCalledWithArguments_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionNotCalledWith('spyIntegrationTestStubFunction', array('foo', 'bar'));
	}

	function test_assertFunctionCalledWith_WhenMethodCalledWithParams_TracksFirstCall()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenMethodCalledWithParams_TracksLastCall()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('baz', 'boo'));
	}
	function test_assertFunctionCalledWith_WhenMethodNotCalledWithParams_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('zar', 'zoo'));
	}

	function test_assertFunctionLastCalledWith_WhenMethodCalledWithParams_Passes()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionLastCalledWith('spyIntegrationTestStubFunction', array('baz', 'boo'));
	}
	function test_assertFunctionLastCalledWith_WhenMethodNotCalledWithParams_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionLastCalledWith('spyIntegrationTestStubFunction', array('foo', 'bar'));
	}

}

function spyIntegrationTestStubFunction()
{
	return Spy::spyIntegrationTestStubFunction();
}

class SpyTraitFakeClassStub {
	public function callSpiedMethod($arg1, $arg2)
	{
		spyIntegrationTestStubFunction($arg1, $arg2);
	}
}
