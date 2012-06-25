<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

define('TEST_API_ROOT', getcwd() . '/../../api/');
define('TEST_MOCK_ROOT', getcwd() . '/../mock/');
define('INCLUDES_DIR', TEST_API_ROOT . 'includes/');
define('MODULES_DIR', INCLUDES_DIR . 'modules/');

// Setup exceptions
include(INCLUDES_DIR . 'exceptions/generic.php');
include(INCLUDES_DIR . 'exceptions/bad_format.php');
include(INCLUDES_DIR . 'exceptions/internal_error.php');
include(INCLUDES_DIR . 'exceptions/unauthorized.php');
include(INCLUDES_DIR . 'exceptions/unimplemented.php');

// Some HTTP headers
define('HTTP_VALID', 'HTTP/1.0 200 Request OK');
define('HTTP_BAD_FORMAT', 'HTTP/1.0 400 Bad Request');
define('HTTP_UNAUTHORIZED', 'HTTP/1.0 401 Unauthorized');
define('HTTP_INTERNAL_ERROR', 'HTTP/1.0 500 Internal Server Error');
define('HTTP_UNIMPLEMENTED', 'HTTP/1.0 501 Not Implemented');