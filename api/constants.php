<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

// Some constants for our own API
global $table_prefixes;
define('API_ROOT', './');
define('INCLUDES_DIR', API_ROOT . 'includes/');
define('MODULES_DIR',  API_ROOT . 'includes/modules/');
// Some constants needed to include phpBB "legally"
define('PHPBB_ROOT', API_ROOT . '../'); 	// Path to phpBB installation
define('PHPBB_PHP_EXT', 'php'); 		// PHP extension

// Some HTTP headers
define('HTTP_VALID', 'HTTP/1.0 200 Request OK');
define('HTTP_BAD_FORMAT', 'HTTP/1.0 400 Bad Request');
define('HTTP_UNAUTHORIZED', 'HTTP/1.0 401 Unauthorized');
define('HTTP_INTERNAL_ERROR', 'HTTP/1.0 500 Internal Server Error');
define('HTTP_UNIMPLEMENTED', 'HTTP/1.0 501 Not Implemented');