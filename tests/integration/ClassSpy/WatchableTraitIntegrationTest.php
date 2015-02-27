<?php namespace UnitTesting\ClassSpy;

class WatchableTraitIntegrationTest extends \PHPUnit_Framework_TestCase {

	function test_WatchableTrait_TracksCalls()
	{
		$instance = new SomeTestClassStub;

		// let's make some calls to this method
		$instance->doSomething('foo');
		$instance->doSomething();
		$instance->doSomethingElse('zee');
		$instance->doSomething('baz', 'boo');

		// all the tracked method and their arguments can be retrieved with getAllMethodCalls()
		$this->assertEquals(array(
			'doSomething' => array(
				array('foo'),
				array(),
				array('baz', 'boo'),
			),
			'doSomethingElse' => array(
				array('zee'),
			),
		), $instance->getAllMethodCalls());

		// get a specific method's argument calls
		$this->assertEquals(array(
			array('foo'),
			array(),
			array('baz', 'boo'),
		), $instance->getMethodCalls('doSomething'));

		// get a specific method's argument calls with an index, remember 2 = second call
		$this->assertEquals(array(), $instance->getMethodCalls('doSomething', 2));

		// get the last argument call
		$this->assertEquals(array('baz', 'boo'), $instance->getMethodCalls('doSomething', 'last'));

		// another way to do the above
		$this->assertEquals(array('baz', 'boo'), $instance->getLastMethodCall('doSomething'));

		// trying to access a call on a tracked method with an index beyond the actual number of calls yields null
		$this->assertNull($instance->getMethodCalls('doSomething', 100));

		// something that is never called will yield null
		$this->assertNull($instance->getMethodCalls('doSomethingThatIsNotTracked'));
	}

	function test_WatchableTrait_CanSetResponses()
	{
		$instance = new SomeTestClassStub;
		// let's set up a simple response
		$instance->setMethodResult('doSomething', 'same result every time');

		// it will always return same thing
		$this->assertEquals('same result every time', $instance->doSomething());
		$this->assertEquals('same result every time', $instance->doSomething('foo'));
		$this->assertEquals('same result every time', $instance->doSomething('foo', 'bar'));

		// return something based upon the parameter
		$instance->setMethodResult('doSomething', function($param)
		{
			return $param . ' result';
		});

		// it will now return value based upon our closure
		$this->assertEquals('foo result', $instance->doSomething('foo'));
		$this->assertEquals('bar result', $instance->doSomething('bar'));
	}
}

class SomeTestClassStub {
	// add this trait to set this class up for testing
	use \UnitTesting\ClassSpy\WatchableTrait;

	function doSomething()
	{
		// this will actuall track the method and its arguments.
		// Be sure to return its value if you want to mock some return values.
		return $this->trackMethodCall();
	}

	function doSomethingElse()
	{
		// we'll just set up another one the same as above for illustration purposes.
		return $this->trackMethodCall();
	}
}
