# UnitTesting\MockeryHelper #
Simple module to help with creating mocks using mockery. You won't have to continually reference `Mockery\Mockery` and keep your tests a little DRY'er.

## Installation ##
* `composer require --dev unit-testing/mockery-helper`
* or in `require-dev` block of `composer.json`, add `"unit-testing/mockery-helper": "*"` and then run `composer update`
* don't forget to `require-dev` `mockery/mockery`

## Usage ##
* In your phpunit test, `use UnitTesting\MockeryHelper\MockeryTrait`.
* in your `tearDown()` method call `$this->closeMocks()`
* When you want to mock something, use `$this->mock()` as an alias for `Mockery::mock()`
* You can also use `$this->spy()` as an alias for `Mockery::spy()`
* Use the result of one of the above function calls as you normally would for your assertions.
* `mockery` method simply forwards any call to the Mockery static. The first argument is the  method, and subsequent arguments are params for the Mockery method.

## Example ##
```
<?php
use UnitTesting\MockeryHelper\MockeryTrait;
class SomeTest extends \PHPUnit_Framework_Testcase {
	use MockeryTrait;
	protected function tearDown()
	{
		$this->cleanUpSomeStuffBeforeEachTest();
		$this->closeMocks();
	}

	function testSomeMethodWithAMock()
	{
		$mock = $this->mock('Child');
		$parent = new ParentThatDependsChild($mock);

		$mock->shouldReceive('doSomething')->once()->with($this->mockery('type', 'string'))->andReturn('result');

		$result = $parent->someMethodWhichCallsDoSomethingOfChild('foo');
		$this->assertEquals('result', $result);
	}
}
```
