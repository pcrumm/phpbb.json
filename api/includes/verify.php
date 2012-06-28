<?php
/**
 * We verify that requests that are attempting to authenticate are
 * properly formatted and contain valid data. If they are not, we
 * generate an exception (our preferred method of dealing with errors
 * here). Otherwise, we'll simply let the rest of the script continue
 * on.
 *
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

namespace phpBBJSON;
class Verify
{
	private $hash;
	private $data;
	private $username;
	private $timestamp;
	
	private $is_auth;
	
	/**
	 * Instantiate the verification engine with a request.
	 * We grab the data we need from this request.
	 *
	 * @param \phpBBJSON\Request $request
	 */
	 
	public function __construct(\phpBBJSON\Request $request)
	{
		// Set our instance variables for convenience
		$this->hash = $request->get_argument('hash');
		$this->data = $request->get_argument('data');
		$this->username = $request->get_argument('username');
		$this->timestamp = $request->get_argument('timestamp');
		
		$this->is_auth = true;
	}
	
	/**
	 * Validates the entirety of the request. Throws an exception if there
	 * are any problems.
	 *
	 * @return bool
	 */
	public function validate($secret)
	{
		// If no required fields are present, we aren't authing--it's fine
		if ($this->no_fields_present())
		{
			$this->is_auth = false;
			return true;
		}
		
		// Some required fields were excluded
		if (!$this->all_fields_present())
		{
			throw new \phpBBJSON\Exception\BadFormat('A required authentication field was excluded from the request.');
		}
		
		// Timestamp check
		if (!$this->validate_timestamp())
		{
			throw new \phpBBJSON\Exception\Unauthorized('The provided timestamp was not valid.');	
		}
		
		// Invalid hash check
		if (!$this->check_hash($secret))
		{
			throw new \phpBBJSON\Exception\Unauthorized('The provided hash is invalid.');
		}
		
		return true;
	}
	
	/**
	 * Allows the script to determine if this is an authenticated request.
	 *
	 * @return bool
	 */
	 public function is_auth()
	 {
		 if (!isset($this->is_auth))
		 {
			 $this->is_auth = !$this->no_fields_present();
		 }
		 
		 return $this->is_auth;
	 }
	
	/**
	 * Checks to see if none of the required authentication fields
	 * are present. This means that "data" will be populated, but
	 * the remaining fields will not be.
	 *
	 * @return bool
	 */
	private function no_fields_present()
	{
		return (empty($this->hash) && empty($this->username) && empty($this->timestamp));
	}
	
	/**
	 * Checks to ensure that all required fields are present.
	 *
	 * @return bool
	 */
	private function all_fields_present()
	{
		return (!empty($this->hash) && !empty($this->data) && !empty($this->username) && !empty($this->timestamp));
	}
	
	/**
	 * Verifies that the instance's hash validates properly.
	 *
	 * @param string $secret previously computed pbkdf2 secret
	 * @return bool
	 */
	private function check_hash($secret)
	{
		$generated_hash = hash_hmac('sha256', $this->timestamp . '-' . $this->username . '-' . $this->data, $secret);
		
		return ($this->hash == $generated_hash);
	}
	
	/**
	 * Checks to see if the set timestamp is valid. Timestamps
	 * are not valid if they are in the future or are more than
	 * sixty seconds older than the current time.
	 *
	 * @return bool
	 */
	private function validate_timestamp()
	{
		$current_time = gmmktime();
		return !($this->timestamp > $current_time || $this->timestamp < ($current_time - 60));
	}
}