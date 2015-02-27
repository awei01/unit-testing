<?php namespace UnitTesting\MockeryHelper;
use Mockery;
trait MockeryTrait {
	protected $mocked;
	/**
	* make a mock
	*/
	protected function mock()
	{
		$args = func_get_args();
		return $this->make('mock', $args);
	}

	/**
	* make a spy
	*/
	protected function spy()
	{
		$args = func_get_args();
		return $this->make('spy', $args);
	}

	/**
	* actually call the mockery static
	*/
	protected function make($type, array $args)
	{
		return call_user_func_array('Mockery::' . $type, $args);
	}

	/**
	* actually close the mocks
	*/
	protected function closeMocks()
	{
		Mockery::close();
	}

	/**
	* call a static method on mockery
	*/
	public function mockery($method /*, $args */)
	{
		$args = func_get_args();
		$method = array_shift($args);
		return $this->make($method, $args);
	}

}
