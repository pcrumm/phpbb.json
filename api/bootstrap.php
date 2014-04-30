<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

include_once('constants.php');

// Setup exceptions
include_once(INCLUDES_DIR . 'exception_handler.php');
set_exception_handler('\phpBBJSON\exception_handler');

include_once(INCLUDES_DIR . 'exceptions/generic.php');
include_once(INCLUDES_DIR . 'exceptions/bad_format.php');
include_once(INCLUDES_DIR . 'exceptions/internal_error.php');
include_once(INCLUDES_DIR . 'exceptions/unauthorized.php');
include_once(INCLUDES_DIR . 'exceptions/unimplemented.php');

// We need our request and response class, so let's grab them now
include_once(INCLUDES_DIR . 'request.php');
include_once(INCLUDES_DIR . 'response.php');

// And our verification system
include_once(INCLUDES_DIR . 'verify.php');

// Include our module system
include_once(INCLUDES_DIR . 'module.php');

// All of our modules need this, so we'll include it
include_once(INCLUDES_DIR . 'modules/base.php');

// Setup phpBB
include_once('bootstrap_phpbb.php');

include(INCLUDES_DIR . 'functions.php');