<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

include('constants.php');

// Setup exceptions
include(INCLUDES_DIR . 'exceptions/generic.php');
include(INCLUDES_DIR . 'exceptions/bad_format.php');
include(INCLUDES_DIR . 'exceptions/internal_error.php');
include(INCLUDES_DIR . 'exceptions/unauthorized.php');
include(INCLUDES_DIR . 'exceptions/unimplemented.php');

// Setup phpBB
include('bootstrap_phpbb.php');