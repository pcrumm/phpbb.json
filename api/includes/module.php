<?php
/**
 * Handles the calling of appropriate modules. We do not handle verification of
 * the request here; this class is just a proxy for information. We handle
 * verification elsewhere.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;
class Module
{
	/**
	 * @var \phpBBJSON\Request
	 */
	private $request;
	
	/**
	 * @var \phpBBJSON\Verify
	 */
	private $verify;
	
	/**
	 * @var \phpBBJSON\phpbb
	 */
	private $phpbb;
	
	/**
	 * Builds the module class. A Request object is passed as the basis.
	 *
	 * @param \phpBBJSON\Request $request
	 */
	public function __construct(\phpBBJSON\Request $request, \phpBBJSON\Verify $verify, \phpBBJSON\phpBB $phpbb)
	{
		$this->request = $request;
		
		// If no module AND interface is specified, this is a blank request:
		// we default to module "default" and interface "default". Otherwise,
		// a blank interface only defaults to "default". We do not touch cases
		// with an empty module but a specified interface.
		if ($request->get_module() == '' && $request->get_interface() == '')
		{
			$request->set_module('default_action');
			$request->set_interface('default_action');
		}
		else if ($request->get_interface() == '')
		{
			$request->set_interface('default_action');
		}
		
		$this->verify = $verify;
		$this->phpbb = $phpbb;
	}
	
	/**
	 * Specifies whether the passed request is valid.
	 *
	 * @return bool
	 */
	public function request_valid()
	{
		$module = $this->request->get_module();
		$interface = $this->request->get_interface();
		
		return ($this->module_allowed($module) && $this->interface_allowed($interface) && $this->module_exists($module) && $this->interface_exists($module, $interface));
	}
	
	/**
	 * Performs the proper routing action based on the passed request.
	 */
	public function route()
	{
		$this->call_interface();
	}
	
	/**
	 * Explicitly set (change) the passed request.
	 *
	 * @param \phpBBJSON\Request $request
	 */
	public function set_request(\phpBBJSON\Request $request)
	{
		$this->request = $request;
	}
	
	/**
	 * Verifies that a given module exists.
	 *
	 * @param string $module_name The name of the module to search for.
	 * @return bool
	 */
	private function module_exists($module_name)
	{
		$filename = $this->get_module_filename($module_name);
		if (file_exists(MODULES_DIR . $filename . '.php'))
		{
			return true;
		}

		return false;
	}
	
	/**
	 * Verifies that the specified module is allowed.
	 * Currently, we hard code a list of disallowed modules.
	 *
	 * @param string $module_name The name to check against
	 * @return bool
	 */
	private function module_allowed($module_name)
	{
		// Utilized the filename version of module names for this check
		$disallowed_modules = array(
			'base',
		);
		
		return !in_array($this->get_module_filename($module_name), $disallowed_modules);
	}
	
	/**
	 * "Translates" module names into the appropriate filename.
	 * The convention here is straightforward:
	 * CamelCase names become camel_case (lowercase, underscore
	 * added at case change)
	 *
	 * @param string $module_name
	 * @return string The translated name
	 */
	private function get_module_filename($module_name)
	{
		return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $module_name));
	}
	
	/**
	 * Verifies that a given interface exists in the specified module.
	 *
	 * @param string $module_name The name of the module to search for
	 * @param string $interface The name of the interface to search for
	 * @return bool
	 */
	private function interface_exists($module_name, $interface)
	{
		if (!$this->module_exists($module_name))
		{
			return false;
		}
		
		include_once(MODULES_DIR . $this->get_module_filename($module_name) . '.php');
		return method_exists('\phpBBJSON\Module\\' . $module_name, $interface);
	}
	
	/**
	 * Determines if an interface is allowed.
	 * An "allowed" interface is any interface that does not
	 * map to an internal function (a function prefixed with __)
	 *
	 * @param string $interface The interface to check
	 * @return bool
	 */
	private function interface_allowed($interface)
	{
		return (strpos($interface, '__' ) === false);
	}
	
	/**
	 * Calls the given interface in the specified module.
	 *
	 * @return mixed The return value from the called function
	 */
	private function call_interface()
	{
		if (!$this->request_valid())
		{
			throw new \phpBBJSON\Exception\Unimplemented('The specified module or interface does not exist.');
		}
		
		$callable_module = '\phpBBJSON\Module\\' . $this->request->get_module();
		
		$module = new $callable_module($this->request, $this->verify, $this->phpbb);
		$module->{$this->request->get_interface()}();
	}
}