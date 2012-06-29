<?php
/**
 * Base class for modules.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON\Module;
class DefaultAction extends \phpBBJSON\Module\Base
{
	/**
	 * We do something a little differently here, and list all of the available
	 * modules.
	 */
	public function default_action()
	{
		$module_base_path = MODULES_DIR;
		
		// Get all of the PHP files in the module directory and reformat their names
		$modules = array();
		if ($handle = opendir($module_base_path))
		{
			while (false !== ($file_name = readdir($handle)))
			{
				if (($position = strpos($file_name, '.php')) !== false)
				{
					// Read up to the file extension
					$file_name = substr($file_name, 0, $position);
					
					// Replace underscores with spaces and reformat
					$file_name = ucwords(str_replace('_', ' ', $file_name));
					$file_name = str_replace(' ', '', $file_name);
					
					$modules[] = $file_name;
				}
			}
		}
		else
		{
			throw new \phpBBJSON\InternalError('Error scanning module directory.');
		}
		
		$this->response->set_header(HTTP_VALID);
		$this->response->set_data(array('modules' => $modules));
		$this->response->response();
	}
}