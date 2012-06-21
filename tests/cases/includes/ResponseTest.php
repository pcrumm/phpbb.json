<?php
/**
 * Tests for phpBBJSON\Response class
 *
 * @package phpbb.json
 * @subpackage tests
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

require_once './../bootstrap.php';
require_once TEST_API_ROOT . 'includes/response.php';

class ResponseTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers \phpBBJSON\Response::get_data
	 */
	public function testGetData()
	{
		$response = $this->getResponse();
		$data = array('asdf'	=> 'jfk');
		$response->set_data($data);
		
		$this->assertEquals($response->get_data(), $data);
	}
	
	/**
	 * @covers \phpBBJSON\Response::merge_data
	 * @depends testGetData
	 *
	 * Verifies that data is merged, and in the correct precedence.
	 */
	public function testMergeDataPrecedence()
	{
		$response = $this->getResponse();
		
		$data_one = array('a' => 'a', 'b' => 'b', 'c' => 'c');
		$data_two = array('a' => 'b', 'b' => 'b', 'd' => 'd');
		$intended_result = array('a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd');
		
		$response->set_data($data_one);
		$response->merge_data($data_two);
		
		$this->assertEquals($response->get_data(), $intended_result);
	}
	
	/**
	 * @covers \phpBBJSON\Response::response
	 * @dataProvider testResponseProvider
	 */
	public function testResponse($result_code, $header, $data)
	{
		$response = $this->getResponse();
		$response->set_header($header);
		$response->set_data($data);
		
		ob_start();
		$response->response();
		$result = ob_get_contents();
		ob_end_clean();
		
		$this->assertEquals($result, json_encode($data));
	}
	
	private function getResponse()
	{
		return new phpBBJSON\Response();
	}
	public function testResponseProvider()
	{
		return array(
			array(
				'status'	=> 200,
				'header'	=> HTTP_VALID,
				'data'		=> array('foo'	=> 'bar'),
			),
			array(
				'status'	=> 400,
				'header'	=> HTTP_BAD_FORMAT,
				'data'		=> array('asdf' => 'jkl; asdf'),
			),
		);
	}
}