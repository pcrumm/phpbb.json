<?php
/**
 * Handles the validation of requests and the handing off to the module.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;
class Request
{
	private $module;
	private $interface;
	
	private $query_data;
	private $arguments;
	
	/**
	 * Populate module, interface, data, and create a list of additional arguments
	 */
	public function __construct($request_source = array())
	{
		// PHP does not appreciate it if we set a superglobal as a 
		// default value to a function, so we do things a little less
		// beautifully and do it ourselves...
		
		if (!is_array($request_source) || empty($request_source))
		{
			$request_source = $_REQUEST;
		}
		
		$this->set_module(isset($request_source['module']) ? $request_source['module'] : '');
		$this->set_interface(isset($request_source['interface']) ? $request_source['interface'] : '');
		
		$request_data = isset($request_source['data']) ? $request_source['data'] : '';
		$this->query_data = json_decode(urldecode($request_data), true);
		$this->arguments = $request_source;
	}
	
	/**
	 * Sets the module for the request to the one specified.
	 *
	 * @param string $module
	 */
	public function set_module($module = '')
	{
		$this->module = $module;
	}
	
	/**
	 * Returns the set module for the request
	 *
	 * @return string
	 */
	public function get_module()
	{
		return $this->module;
	}
	
	/**
	 * Sets the interface for the request to the one specified.
	 *
	 * @param string $interface
	 */
	public function set_interface($interface = '')
	{
		$this->interface = $interface;
	}
	
	/**
	 * Returns the set interface for the request
	 *
	 * @return string
	 */
	public function get_interface()
	{
		return $this->interface;
	}
	
	/**
	 * Provides an array of passed data elements.
	 *
	 * @return array Request data
	 */
	public function get_datum()
	{
		return $this->query_data;
	}
	
	/**
	 * A convenience accessor for individual data arguments.
	 *
	 * @param string $key Key in the data array for the element you seek
	 * @return mixed The requested data element, or "" if the element does not exist.
	 */
	public function get_data($key)
	{
		return (isset($this->query_data[$key])) ? $this->query_data[$key] : '';
	}
	
	/**
	 * Provides the full array of arguments passed to the request.
	 *
	 * @return array Request arguments
	 */
	public function get_arguments()
	{
		return $this->arguments;
	}
	
	/**
	 * A convenience accessor for the individual argument elements.
	 *
	 * @param string $argument Key in the argument array for the element you seek
	 * @return mixed The requested data element, or "" if the element does not exist.
	 */
	public function get_argument($argument)
	{
		return (isset($this->arguments[$argument])) ? $this->arguments[$argument] : '';
	}
}