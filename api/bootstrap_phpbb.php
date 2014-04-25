<?php
/**
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

/**
 */

define('IN_PHPBB', true);

// Setup the variables we need to include phpBB the proper way
$phpbb_root_path = PHPBB_ROOT;
$phpEx = PHPBB_PHP_EXT;

// DB configuration
if (file_exists($phpbb_root_path . 'common.' . $phpEx))
{
	include($phpbb_root_path . 'common.' . $phpEx);
}
else
{
	throw new \phpBBJSON\Exception\InternalError('Common file not found');
}

// And the DBAL
/*if (isset($dbms))
{
	include ($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
}
else
{
	throw new \phpBBJSON\Exception\InternalError('Config file not properly formatted');
}*/

// Setup our phpBB "container"
include(INCLUDES_DIR . 'phpbb.' . $phpEx);
$phpbb = new phpBBJSON\phpbb();

// Setup the dbal
$dbal_driver = 'dbal_' . $dbms;
//$phpbb_db = new $dbal_driver();
//$phpbb_db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false);
//unset($dbpasswd); // For security purposes
$phpbb->set_user($user);
$phpbb->set_auth($auth);
$phpbb->set_db($db);
$phpbb->set_config($config);

// Table names
define('API_SECRET',                            $table_prefix . 'api_secret');
define('ACL_GROUPS_TABLE',			$table_prefix . 'acl_groups');
define('ACL_OPTIONS_TABLE',			$table_prefix . 'acl_options');
define('ACL_ROLES_DATA_TABLE',                  $table_prefix . 'acl_roles_data');
define('ACL_ROLES_TABLE',			$table_prefix . 'acl_roles');
define('ACL_USERS_TABLE',			$table_prefix . 'acl_users');
define('ATTACHMENTS_TABLE',			$table_prefix . 'attachments');
define('BANLIST_TABLE',				$table_prefix . 'banlist');
define('BBCODES_TABLE',				$table_prefix . 'bbcodes');
define('BOOKMARKS_TABLE',			$table_prefix . 'bookmarks');
define('BOTS_TABLE',				$table_prefix . 'bots');
define('CONFIG_TABLE',				$table_prefix . 'config');
define('CONFIRM_TABLE',				$table_prefix . 'confirm');
define('DISALLOW_TABLE',			$table_prefix . 'disallow');
define('DRAFTS_TABLE',				$table_prefix . 'drafts');
define('EXTENSIONS_TABLE',			$table_prefix . 'extensions');
define('EXTENSION_GROUPS_TABLE',	$table_prefix . 'extension_groups');
define('FORUMS_TABLE',				$table_prefix . 'forums');
define('FORUMS_ACCESS_TABLE',		$table_prefix . 'forums_access');
define('FORUMS_TRACK_TABLE',		$table_prefix . 'forums_track');
define('FORUMS_WATCH_TABLE',		$table_prefix . 'forums_watch');
define('GROUPS_TABLE',				$table_prefix . 'groups');
define('ICONS_TABLE',				$table_prefix . 'icons');
define('LANG_TABLE',				$table_prefix . 'lang');
define('LOG_TABLE',					$table_prefix . 'log');
define('LOGIN_ATTEMPT_TABLE',		$table_prefix . 'login_attempts');
define('MODERATOR_CACHE_TABLE',		$table_prefix . 'moderator_cache');
define('MODULES_TABLE',				$table_prefix . 'modules');
define('POLL_OPTIONS_TABLE',		$table_prefix . 'poll_options');
define('POLL_VOTES_TABLE',			$table_prefix . 'poll_votes');
define('POSTS_TABLE',				$table_prefix . 'posts');
define('PRIVMSGS_TABLE',			$table_prefix . 'privmsgs');
define('PRIVMSGS_FOLDER_TABLE',		$table_prefix . 'privmsgs_folder');
define('PRIVMSGS_RULES_TABLE',		$table_prefix . 'privmsgs_rules');
define('PRIVMSGS_TO_TABLE',			$table_prefix . 'privmsgs_to');
define('PROFILE_FIELDS_TABLE',		$table_prefix . 'profile_fields');
define('PROFILE_FIELDS_DATA_TABLE',	$table_prefix . 'profile_fields_data');
define('PROFILE_FIELDS_LANG_TABLE',	$table_prefix . 'profile_fields_lang');
define('PROFILE_LANG_TABLE',		$table_prefix . 'profile_lang');
define('RANKS_TABLE',				$table_prefix . 'ranks');
define('REPORTS_TABLE',				$table_prefix . 'reports');
define('REPORTS_REASONS_TABLE',		$table_prefix . 'reports_reasons');
define('SEARCH_RESULTS_TABLE',		$table_prefix . 'search_results');
define('SEARCH_WORDLIST_TABLE',		$table_prefix . 'search_wordlist');
define('SEARCH_WORDMATCH_TABLE',	$table_prefix . 'search_wordmatch');
define('SESSIONS_TABLE',			$table_prefix . 'sessions');
define('SESSIONS_KEYS_TABLE',		$table_prefix . 'sessions_keys');
define('SITELIST_TABLE',			$table_prefix . 'sitelist');
define('SMILIES_TABLE',				$table_prefix . 'smilies');
define('STYLES_TABLE',				$table_prefix . 'styles');
define('STYLES_TEMPLATE_TABLE',		$table_prefix . 'styles_template');
define('STYLES_TEMPLATE_DATA_TABLE',$table_prefix . 'styles_template_data');
define('STYLES_THEME_TABLE',		$table_prefix . 'styles_theme');
define('STYLES_IMAGESET_TABLE',		$table_prefix . 'styles_imageset');
define('STYLES_IMAGESET_DATA_TABLE',$table_prefix . 'styles_imageset_data');
define('TOPICS_TABLE',				$table_prefix . 'topics');
define('TOPICS_POSTED_TABLE',		$table_prefix . 'topics_posted');
define('TOPICS_TRACK_TABLE',		$table_prefix . 'topics_track');
define('TOPICS_WATCH_TABLE',		$table_prefix . 'topics_watch');
define('USER_GROUP_TABLE',			$table_prefix . 'user_group');
define('USERS_TABLE',				$table_prefix . 'users');
define('WARNINGS_TABLE',			$table_prefix . 'warnings');
define('WORDS_TABLE',				$table_prefix . 'words');
define('ZEBRA_TABLE',				$table_prefix . 'zebra');