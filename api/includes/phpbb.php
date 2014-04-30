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
	private $auth;
        private $user;
        private $config;
        
	public function set_db(\dbal $db)
	{
		$this->db = $db;
	}
	
	public function get_db()
	{
		return $this->db;
	}
        
        public function set_auth(\auth $auth)
        {
            $this->auth = $auth;
        }
        
        public function get_auth()
        {
            return $this->auth;
        }
        
        public function set_user(\user $user)
        {
            $this->user = $user;
        }
        
        public function get_user()
        {
            return $this->user;
        }
        
        public function set_config($config)
        {
            $this->config = $config;
        }
        
        public function get_config()
        {
            return $this->config;
        }
}