<?php
/**
 * Tests for phpBBJSON\Request class
 *
 * @package phpbb.json
 * @subpackage tests
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

require_once './../bootstrap.php';
require_once TEST_API_ROOT . 'includes/request.php';

class RequestTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Ensure that an invalid module generates an error.
	 *
	 * @covers \phpBBJSON\Response::set_module
	 * @expectedException \phpBBJSON\Exception\BadFormat
	 */
	public function testInvalidModule()
	{
		$request = new \phpBBJSON\Request();
		ob_start(); // So any further output is surpressed
		$request->set_module('hello!');
	}
	
	/**
	 * Ensure that an invalid interface generates an error.
	 *
	 * @covers \phpBBJSON\Response::set_interface
	 * @expectedException \phpBBJSON\Exception\BadFormat
	 */
	public function testInvalidInterface()
	{
		$request = new \phpBBJSON\Request();
		ob_start(); // So any further output is surpressed
		$request->set_interface('world!');
	}
	
	/**
	 * Ensure that provided data is properly set and retrieved.
	 *
	 * @covers \phpBBJSON\Response::get_datum
	 * @covers \phpBBJSON\Response::_construct
	 */
	public function testGetDatum()
	{
		$data = $this->sample_valid_request_data();
		$request = $this->build_request($data);
		
		$this->assertEquals($request->get_datum(), $this->sample_valid_request_data(true));
	}
	
	/**
	 * Ensure that provided data is properly retrieved on a key basis.
	 * This, conveniently, also ensures that the constructor is functioning properly.
	 *
	 * @covers \phpBBJSON\Response::get_data
	 * @covers \phpBBJSON\Response::__construct
	 */
	public function testGetData()
	{
		$data = $this->sample_valid_request_data(true);
		$request_data = $this->sample_valid_request_data();
		$request = $this->build_request($request_data);
		
		foreach ($data as $key => $value)
		{
			$this->assertEquals($data[$key], $request->get_data($key));
		}
	}
	
	/**
	 * Ensure that explicitly setting and retrieving the module works as expected.
	 *
	 * @covers \phpBBJSON\Response::set_module
	 * @covers \phpBBJSON\Response::get_module
	 */
	public function testSetAndRetrieveModule()
	{
		$new_module = 'modularific';
		$request = new \phpBBJSON\Request();
		
		$request->set_module($new_module);
		$this->assertEquals($request->get_module(), $new_module);
	}
	
	public function testSetAndRetrieveInterface()
	{
		$new_interface = 'interfacio';
		$request = new \phpBBJSON\Request();
		
		$request->set_interface($new_interface);
		$this->assertEquals($request->get_interface(), $new_interface);
	}
	
	/**
	 * Ensure that arguments are successfully sent and retrieved.
	 *
	 * @covers \phpBBJSON\Response::get_arguments
	 * @covers \phpBBJSON\Response::get_argument
	 */
	public function testArgumentRetrieval()
	{
		$request_data = $this->sample_valid_request_data();
		$request = $this->build_request($request_data);
		
		$this->assertEquals($request->get_argument('username'), 'pcrumm');
		$this->assertEquals($request->get_arguments(), $request_data);
	}
	
	/**
	 * Ensure that blank passed requests are successfully handled.
	 *
	 * @covers \phpBBJSON\Response::__construct
	 * @depends testGetDatum
	 * @depends testGetData
	 * @depends testSetAndRetrieveModule
	 * @depends testSetAndRetrieveInterface
	 * @depends testArgumentRetrieval
	 */
	public function testBlankRequestData()
	{
		$_REQUEST = array(
			'module'	=> 'test',
			'interface'	=> 'testing',
			'username'	=> 'phil',
			'data'		=> urlencode(json_encode(array('foo' => 'bar'))),
		);
		
		$request = new \phpBBJSON\Request();
		
		$this->assertEquals($request->get_module(), $_REQUEST['module']);
		$this->assertEquals($request->get_interface(), $_REQUEST['interface']);
		$this->assertEquals($request->get_argument('username'), 'phil');
		$this->assertEquals($request->get_data('foo'), 'bar');
	}
	
	/**
	 * Build a Request object for testing using the provided data.
	 *
	 * @param $request_data The data to utilize for the request
	 * @return \phpBBJSON\Request
	 */
	private function build_request($request_data)
	{
		$request = new \phpBBJSON\Request($request_data);
		
		return $request;
	}
	
	/**
	 * Provide sample data for a fully valid request.
	 *
	 * @param bool $return_data_array Return only the data used for populating "data" element
	 * @return array
	 */
	private function sample_valid_request_data($return_data_array = false)
	{
		$data = array('foo' => 'bar', 'hello' => 'world', 'name' => 'Phil Crumm');
		if ($return_data_array)
		{
			return $data;
		}
		
		return array(
			'module'	=> 'test',
			'interface'	=> 'testing',
			'data'		=> urlencode(json_encode($data)),
			'username'	=> 'pcrumm',
		);
	}
}