<?php
/**
 * Handles the formatting of responses.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;
class Response
{
	private $data;
	private $header;
	
	public function set_header($header_code)
	{
		$this->header = $header_code;
	}
	
	public function set_data($data)
	{
		$this->data = $data;
	}
	
	public function merge_data($data)
	{
		$this->data = array_merge($data, $this->data);
	}
	
	public function get_data()
	{
		return $this->data;
	}
	
	public function response()
	{
	 	@header($this->header);
		print json_encode($this->data);
	}
}