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
	
	/**
	 * Sets a full HTTP header for response. By convention, this
	 * should be a full header to return the appropriate status code.
	 *
	 * @param string $header_code Full HTTP header to return. See constants.php for suggestions.
	 */
	public function set_header($header_code)
	{
		$this->header = $header_code;
	}
	
	/**
	 * Sets return data; this will be json_encode()ed and output 
	 * at the end of the request.
	 *
	 * @argument array $data Response data
	 */
	public function set_data($data)
	{
		$this->data = $data;
	}
	
	/**
	 * Adds additional data to the current data store by merging two arrays.
	 * By convention, the previously set data should take precedence.
	 *
	 * @param array $data Data to merge into the existing
	 */
	public function merge_data($data)
	{
		// This should prevent any notices from being generated
		if (!is_array($this->data))
		{
			$this->data = array();
		}
		
		$this->data = array_merge($data, $this->data);
	}
	
	public function get_data()
	{
		return $this->data;
	}
	
	/**
	 * Generate a response from the set values. The appropriate HTTP header
	 * will be output, and the set data will be printed directly as JSON.
	 */
	public function response()
	{
	 	@header($this->header);
		print json_encode($this->data);
	}
}