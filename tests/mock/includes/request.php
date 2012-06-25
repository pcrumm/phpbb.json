<?php
/**
 * Mock request class for testing purposes.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON\Mock;
require_once TEST_API_ROOT . 'includes/request.php';
class Request extends \phpBBJSON\Request
{	
	/**
	 * Populate module, interface, data, and create a list of additional arguments
	 */
	public function __construct($module = '', $interface = '', $query_data = array(), $arguments = array())
	{
		$data_source = array(
			'module'	=> $module,
			'interface'	=> $interface,
			'data'		=> urlencode(json_encode($query_data)),
		);
		
		$data_source = array_merge($data_source, $arguments);
		
		parent::__construct($data_source);
	}
}