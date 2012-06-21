<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

// Some constants for our own API

define('API_ROOT', './');
define('INCLUDES_DIR', API_ROOT . 'includes/');
define('MODULES_DIR',  API_ROOT . 'modules/');

// Some constants needed to include phpBB "legally"
define('PHPBB_ROOT', API_ROOT . '../'); 	// Path to phpBB installation
define('PHPBB_PHP_EXT', 'php'); 		// PHP extension