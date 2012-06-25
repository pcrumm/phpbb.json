<?php
/**
 * Tests for phpBBJSON\Module class
 *
 * @package phpbb.json
 * @subpackage tests
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

require_once './../bootstrap.php';
require_once TEST_API_ROOT . 'includes/module.php';
require_once TEST_MOCK_ROOT . 'includes/request.php';
require_once MODULES_DIR . 'base.php';

class ModuleTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Verify that an error is generated if a module does not exist.
	 *
	 * @covers \phpBBJSON\Module::request_valid
	 * @covers \phpBBJSON\Module::set_request
	 * @covers \phpBBJSON\Module::module_exists
	 * @covers \phpBBJSON\Module::get_module_filename
	 * @covers \phpBBJSON\Module::interface_exists
	 */
	public function testInvalidModule()
	{
		$request = $this->buildRequest('fake', 'superfake');
		$module = $this->buildModule($request);
		
		$this->assertEquals($module->request_valid(), false);
	}
	
	/**
	 * Verify that an error is generated if an interface does not exist.
	 *
	 * @covers \phpBBJSON\Module::request_valid
	 * @covers \phpBBJSON\Module::set_request
	 * @covers \phpBBJSON\Module::module_exists
	 * @covers \phpBBJSON\Module::get_module_filename
	 * @covers \phpBBJSON\Module::interface_exists
	 */
	public function testInvalidInterface()
	{
		$request = $this->buildRequest('default_action', 'fake');
		$module = $this->buildModule($request);
		
		$this->assertEquals($module->request_valid(), false);
	}
	
	/**
	 * Verify that a successful request occurs if a module does exist.
	 *
	 * @covers \phpBBJSON\Module::request_valid
	 * @covers \phpBBJSON\Module::set_request
	 * @covers \phpBBJSON\Module::module_exists
	 * @covers \phpBBJSON\Module::get_module_filename
	 * @covers \phpBBJSON\Module::interface_exists
	 */
	public function testValidRequest()
	{
		$request = $this->buildRequest('default_action', 'default_action');
		$module = $this->buildModule($request);
		
		$this->assertEquals($module->request_valid(), true);
	}
	
	/**
	 * Verify that a success call can occur on a successful request.
	 *
	 * @covers \phpBBJSON\Module::set_request
	 * @covers \phpBBJSON\Module::module_exists
	 * @covers \phpBBJSON\Module::get_module_filename
	 * @covers \phpBBJSON\Module::interface_exists
	 * @covers \phpBBJSON\Module::call_interface
	 * @covers \phpBBJSON\Module::route
	 */
	public function testValidRequestAndCall()
	{
		$request = $this->buildRequest('default_action', 'default_action');
		$module = $this->buildModule($request);
		
		ob_start();
		$module->route();
		$result = ob_get_clean();
		
		$result = json_decode($result, true);
		$this->assertEquals(isset($result['modules']), true);
	}
	
	/**
	 * Verify that a route call on an invalid route throws an exception.
	 *
	 * @covers \phpBBJSON\Module::route
	 * @expectedException \phpBBJSON\Exception\Unimplemented
	 */
	public function testInvalidRequestCall()
	{
		$request = $this->buildRequest('fake_route', 'faking');
		$module = $this->buildModule($request);
		
		ob_start(); // Suppress the output
		$module->route();
	}
	
	/**
	 * Provides a mocked module handler for testing.
	 *
	 * @argument \phpBBJSON\Mock\Request $request
	 */
	private function buildModule(\phpBBJSON\Mock\Request $request)
	{
		$module = new \phpBBJSON\Module($request);
		
		return $module;
	}
	
	/**
	 * Provides a mocked request for testing.
	 *
	 * @param string $module
	 * @param string $interface
	 * @param array $query_data
	 * @param array $arguments
	 */
	private function buildRequest($module = '', $interface = '', $query_data = array(), $arguments = array())
	{
		return new \phpBBJSON\Mock\Request($module, $interface, $query_data, $arguments);
	}
}