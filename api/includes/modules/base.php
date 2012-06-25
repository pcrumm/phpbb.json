<?php
/**
 * Base class for modules.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON\Module;
class Base
{
	private $response;
	
	public function __construct()
	{
		$this->response = new \phpBBJSON\Response();
	}
	
	/**
	 * The default method for any class should list its interfaces; that is,
	 * its publicly available methods.
	 *
	 * @param \phpBBJSON\Request $request
	 */
	public function default_action(\phpBBJSON\Request $request)
	{
		$methods = get_class_methods($this);
		
		$this->response->set_header(HTTP_VALID);
		$this->response->set_data(array('interfaces' => $methods));
		$this->response->response();
	}
}