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

		$result = $this->spy->getRecorder('spyIntegrationTestStubFunction')->getCalls();

		$this->assertEquals(array(array('foo', 'bar')), $result);
	}
	function test_callingSpiedFunctionFromClassMethod_ProperlyRecordsMethodAndCalls()
	{
		$myclass = new SpyTraitFakeClassStub();
		$myclass->callSpiedMethod('foo', 'bar');

		$result = $this->spy->getRecorder('spyIntegrationTestStubFunction')->getCalls();

		$this->assertEquals(array(array('foo', 'bar')), $result);
	}
	function test_setFunctionResultFromStatic_SetsResultForRecorder()
	{
		Spy::setFunctionResult('spyIntegrationTestStubFunction', 'result');

		$result = spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertEquals('result', $result);
	}
	function test_setFunctionResultFromSpyProperty_SetsResultForRecorder()
	{
		$this->spy->setFunctionResult('spyIntegrationTestStubFunction', 'result');

		$result = spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertEquals('result', $result);
	}
	function test_setResultOnSpyKey_SetsResultForRecorder()
	{
		$this->spy['spyIntegrationTestStubFunction']->setResult('result');

		$result = spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertEquals('result', $result);
	}
	function test_offsetSetOnSpy_SetsResultForRecorder()
	{
		$this->spy['spyIntegrationTestStubFunction'] = 'result';

		$result = spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertEquals('result', $result);
	}
	function test_subesquentTestImplicitlyFlushesRecordersOnSpy()
	{
		$result = $this->spy->getRecorders();

		$this->assertEquals(array(), $result);
	}
	function test_assertFunctionNotCalled_WhenFunctionNotCalled_Passes()
	{
		$this->assertFunctionNotCalled('spyIntegrationTestStubFunction');
	}
	function test_assertFunctionNotCalled_WhenFunctionIsCalled_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionNotCalled('spyIntegrationTestStubFunction');
	}

	function test_assertFunctionNotCalledWith_WhenFunctionNotCalledWithParams_Passes()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->assertFunctionNotCalledWith('spyIntegrationTestStubFunction', array('bar', 'baz'));
	}
	function test_assertFunctionNotCalledWith_WhenFunctionIsCalledWithParams_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionNotCalledWith('spyIntegrationTestStubFunction', array('foo', 'bar'));
	}

	function test_assertFunctionCalledWith_WhenFunctionCalledWithParams_TracksFirstCall()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('foo', 'bar'));
	}
	function test_assertFunctionCalledWith_WhenFunctionCalledWithParams_TracksLastCall()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('baz', 'boo'));
	}
	function test_assertFunctionCalledWith_WhenFunctionNotCalledWithParams_FailsAsExpected()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->setExpectedException('PHPUnit_Framework_AssertionFailedError');

		$this->assertFunctionCalledWith('spyIntegrationTestStubFunction', array('zar', 'zoo'));
	}

	function test_assertFunctionLastCalledWith_WhenFunctionCalledWithParams_Passes()
	{
		spyIntegrationTestStubFunction('foo', 'bar');
		spyIntegrationTestStubFunction('baz', 'boo');

		$this->assertFunctionLastCalledWith('spyIntegrationTestStubFunction', array('baz', 'boo'));
	}
	function test_assertFunctionLastCalledWith_WhenFunctionNotCalledWithParams_FailsAsExpected()
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
