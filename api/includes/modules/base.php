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
	protected $response;
	protected $request;
	protected $verify;
	protected $phpbb;
	
	public function __construct(\phpBBJSON\Request $request, \phpBBJSON\Verify $verify, \phpbBJSON\phpBB $phpbb)
	{
		$this->response = new \phpBBJSON\Response();
		$this->request = $request;
		$this->verify = $verify;
		$this->phpbb = $phpbb;
	}
	
	/**
	 * The default method for any class should list its interfaces; that is,
	 * its publicly available methods.
	 *
	 * @param \phpBBJSON\Request $request
	 */
	public function default_action()
	{
		$methods = get_class_methods($this);
		
		$this->response->set_header(HTTP_VALID);
		$this->response->set_data(array('interfaces' => $methods));
		$this->response->response();
	}
}