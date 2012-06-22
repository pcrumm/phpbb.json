<?php
/**
 * Handles exceptions relating to unauthorized errors (HTTP Error 401)
 *
 * @package phpbb.json
 * @subpackage exceptions
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON\Exception;
include_once('generic.php');

class Unauthorized extends \phpBBJSON\Exception\GenericException
{
	/**
	 * Generate a proper response (and include the error code in the 'error' field) and quit
	 *
	 * @param string $message Error message
	 * @param int $code Error code
	 * @param Exception $previous Previous unhandled exception
	 */
	public function __construct($message = '', $code = 0, Exception $previous = NULL)
	{
		$this->generate_response(HTTP_UNAUTHORIZED, $message);
	}
}