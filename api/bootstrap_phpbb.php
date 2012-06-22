<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 */

define('IN_PHPBB', true);

// Setup the variables we need to include phpBB the proper way
$phpbb_root_path = PHPBB_ROOT;
$phpEx = PHPBB_PHP_EXT;

// DB configuration
if (file_exists($phpbb_root_path . 'config.' . $phpEx))
{
	include($phpbb_root_path . 'config.' . $phpEx);
}
else
{
	throw new \phpBBJSON\Exception\InternalError('Config file not found');
}

// And the DBAL
if (isset($dbms))
{
	include ($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
}
else
{
	throw new \phpBBJSON\Exception\InternalError('Config file not properly formatted');
}

// Setup our phpBB "container"
include(INCLUDES_DIR . 'phpbb.' . $phpEx);
$phpbb = new phpBBJSON\phpbb();

// Setup the dbal
$dbal_driver = 'dbal_' . $dbms;
$phpbb_db = new $dbal_driver();
$phpbb_db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false);
unset($dbpasswd); // For security purposes

$phpbb->set_db($phpbb_db);