<?php
/**
 * Exception handler for phpbb.json. Does nothing but prevent the "uncaught exception"
 * error from being output.
 *
 * @package phpbb.json
 * @subpackage exceptions
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;

/**
 * Suppress the uncaught exception error.
 *
 * @param Exception $exception
 */
function exception_handler($exception)
{
	return; // Do nothing
}