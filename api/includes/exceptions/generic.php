<?php
/**
 * Generic exception format for errors
 *
 * @package phpbb.json
 * @subpackage exceptions
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON\Exception;
include_once(INCLUDES_DIR . 'response.php');
class GenericException extends \Exception
{
	/**
	 * Generate a response and quit.
	 *
	 * @param string $header HTTP header to output
	 * @param string $message Error message to output
	 */
	protected function generate_response($header, $message)
	{
		$response = new \phpBBJSON\Response();
		$response->set_header(HTTP_BAD_FORMAT);
		$response->set_data(array('error', $message));
		
		$response->response();
	}
}