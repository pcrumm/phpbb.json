<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

include_once('constants.php');

// Setup exceptions
include_once(INCLUDES_DIR . 'exceptions/generic.php');
include_once(INCLUDES_DIR . 'exceptions/bad_format.php');
include_once(INCLUDES_DIR . 'exceptions/internal_error.php');
include_once(INCLUDES_DIR . 'exceptions/unauthorized.php');
include_once(INCLUDES_DIR . 'exceptions/unimplemented.php');

// We need our request and response class, so let's grab them now
include_once(INCLUDES_DIR . 'request.php');
include_once(INCLUDES_DIR . 'response.php');

// All of our modules need this, so we'll include it
include_once(INCLUDES_DIR . 'modules/base.php');

// Setup phpBB
include('bootstrap_phpbb.php');