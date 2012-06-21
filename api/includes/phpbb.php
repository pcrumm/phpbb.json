<?php
/**
 * A container class for some phpBB functionality. This allows us to pass
 * around phpBB "instances" and not pollute the global namespace.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;

class phpBB
{
	private $db;
	
	public function set_db(\dbal $db)
	{
		$this->db = $db;
	}
	
	public function get_db()
	{
		return $this->db;
	}
}