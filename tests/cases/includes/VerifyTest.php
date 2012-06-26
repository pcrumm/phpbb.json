<?php
/**
 * Tests for phpBBJSON\Verify class
 *
 * @package phpbb.json
 * @subpackage tests
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Phil Crumm pcrumm@p3net.net
 */

require_once './../bootstrap.php';
require_once TEST_MOCK_ROOT . 'includes/request.php';
require_once TEST_API_ROOT . 'includes/verify.php';

class VerifyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Calculating the secret is expensive, and we use the same
	 * one for each test, so we save it.
	 */
	private $secret;
	
	/**
	 * We generate a valid request to verify right off that everything
	 * is working properly.
	 *
	 * @covers \phpBBJSON\Verify::validate
	 * @covers \phpBBJSON\Verify::is_auth
	 */
	public function testValidRequest()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
				
		$request = $this->build_request($username, $data, $timestamp, $hash);
		
		$verify = new \phpBBJSON\Verify($request);
		
		$this->assertEquals($verify->validate($secret), true);
		$this->assertEquals($verify->is_auth(), true);
	}
	
	/**
	 * We generate a request that does not require authentication and
	 * verify that is is said to be verified.
	 *
	 * @covers \phpBBJSON\Verify::validate
	 * @covers \phpBBJSON\Verify::is_auth
	 */
	public function testNonAuthRequest()
	{
		$request = $this->build_request('', array('test' => 'test'), '', '');
		$verify = new \phpBBJSON\Verify($request);
		
		$this->assertEquals($verify->validate(''), true);
		$this->assertEquals($verify->is_auth(), false);
	}
	
	/**
	 * Verify that an otherwise valid request with an excluded timestamp
	 * is not valid.
	 *
	 * @expectedException \phpBBJSON\Exception\BadFormat
	 */
	public function testExcludedTimestamp()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
		
		$request = $this->build_request($username, $data, '', $hash);
		$verify = new \phpBBJSON\Verify($request);
		
		ob_start(); // Suppress the exception output
		$verify->validate($secret);
	}
	
	/**
	 * Verify that an otherwise valid request with an excluded username
	 * is not valid.
	 *
	 * @expectedException \phpBBJSON\Exception\BadFormat
	 */
	public function testExcludedUsername()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
		
		$request = $this->build_request('', $data, $timestamp, $hash);
		$verify = new \phpBBJSON\Verify($request);
		
		ob_start(); // Suppress the exception output
		$verify->validate($secret);
	}
	
	/**
	 * Verify that an otherwise valid request with an excluded hash
	 * is not valid.
	 *
	 * @expectedException \phpBBJSON\Exception\BadFormat
	 */
	public function testExcludedHash()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
		
		$request = $this->build_request($username, $data, $timestamp, '');
		$verify = new \phpBBJSON\Verify($request);
		
		ob_start(); // Suppress the exception output
		$verify->validate($secret);
	}
	
	/**
	 * Verify that an otherwise valid request with an invalid timestamp
	 * is not valid.
	 *
	 * @expectedException \phpBBJSON\Exception\Unauthorized
	 */
	public function testInvaidTimestamp()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
		
		$request = $this->build_request($username, $data, $timestamp - 100, $hash);
		$verify = new \phpBBJSON\Verify($request);
		
		ob_start(); // Suppress the exception output
		$verify->validate($secret);
	}
	
	/**
	 * Verify that an otherwise valid request with an invalid hash
	 * is not valid.
	 *
	 * @expectedException \phpBBJSON\Exception\Unauthorized
	 */
	public function testInvalidHash()
	{
		list($username, $password, $timestamp, $data, $secret, $hash) = $this->valid_request_information();
		
		$request = $this->build_request($username, $data, $timestamp, 'invalidhash');
		$verify = new \phpBBJSON\Verify($request);
		
		ob_start(); // Suppress the exception output
		$verify->validate($secret);
	}
	
	/**
	 * Generate an array of information for a valid request.
	 *
	 * @return array Valid request information, formatted as following:
	 *					array(0 => $username, 1 => $password,
	 *						2 => $timestamp, 3 => $data,
	 *						4 => $secret, 5 => $hash
	 */
	private function valid_request_information()
	{
		$username = 'username';
		$password = 'password';
		$secret = (empty($this->secret)) ? $this->build_secret($username, $password) : $this->secret;
		$data = array(
			'a'	=> 'a',
			'b'	=> 'b',
			'd'	=> 'e',
		);
		$timestamp = gmmktime();
		$hash = $this->build_hash($timestamp, $username, $data, $secret);
		
		// If the secret class variable isn't set, let's do that now
		$this->secret = (empty($this->secret)) ? $secret : $this->secret;

		return array(
			0	=> $username,
			1	=> $password,
			2	=> $timestamp,
			3	=> $data,
			4	=> $secret,
			5	=> $hash
		);
	}
	
	/**
	 * Generate the secret used to sign our request. It is a pbkdf2-derived
	 * key consisting of the password salted with the username (10,000
	 * iterations).
	 *
	 * @param string $username Username for the request (used as a salt)
	 * @param string $password Password for the request
	 * @return string Generated pbkdf2 hahs
	 */
	private function build_secret($username, $password)
	{
		return $this->pbkdf2('sha256', $password, $username, 10000);
	}
	
	/**
	 * Generate a hmac (sha256) hash to "sign" a request. The format for this
	 * hash is specified in our API specification.
	 *
	 * @param int $timestamp UTC UNIX timestamp for request
	 * @param string $username Username for authenticating user
	 * @param array $data Data for request
	 * @param string $secret The PBKDF2 secret signing the request
	 * @return string Generated hash for request
	 */
	private function build_hash($timestamp, $username, $data, $secret)
	{
		return hash_hmac('sha256', $timestamp . '-' . $username . '-' . urlencode(json_encode($data)), $secret);
	}
	
	/**
	 * Build a mock request object for an authenticated request.
	 *
	 * @param string $username Username for authenticating user
	 * @param array $data Request data
	 * @param int $time UTC UNIX timestamp for request
	 * @param string $hash hmac (sha256) hash verifying request
	 * @return \phpBBJSON\Mock\Request Generated request
	 */
	private function build_request($username, $data, $time, $hash)
	{
		$request = new \phpBBJSON\Mock\Request('module', 'interface', $data, array(
			'data'		=> urlencode(json_encode($data)),
			'username'	=> $username,
			'hash'		=> $hash,
			'timestamp'	=> $time,
		));
		
		return $request;
	}
	
	/**
	 * Implementation of the PBKDF2 key derivation function as described in
	 * RFC 2898.
	 * (Via http://www.php.net/manual/en/function.hash-hmac.php#108966)
	 *
	 * @param string $PRF Hash algorithm.
	 * @param string $P Password.
	 * @param string $S Salt.
	 * @param int $c Iteration count.
	 * @param mixed $dkLen Derived key length (in octets). If $dkLen is FALSE
	 *                     then length will be set to $PRF output length (in
	 *                     octets).
	 * @param bool $raw_output When set to TRUE, outputs raw binary data. FALSE
	 *                         outputs lowercase hexits.
	 * @return mixed Derived key or FALSE if $dkLen > (2^32 - 1) * hLen (hLen
	 *               denotes the length in octets of $PRF output).
	 */
	private function pbkdf2($PRF, $P, $S, $c, $dkLen = false, $raw_output = false) {
	    //default $hLen is $PRF output length
	    $hLen = strlen(hash($PRF, '', true));
	    if ($dkLen === false) $dkLen = $hLen;
	
	    if ($dkLen <= (pow(2, 32) - 1) * $hLen) {
	        $DK = '';
	
	        //create key
	        for ($block = 1; $block <= $dkLen; $block++) {
	            //initial hash for this block
	            $ib = $h = hash_hmac($PRF, $S.pack('N', $block), $P, true);
	
	            //perform block iterations
	            for ($i = 1; $i < $c; $i++) {
	                $ib ^= ($h = hash_hmac($PRF, $h, $P, true));
	            }
	
	            //append iterated block
	            $DK .= $ib;
	        }
	
	        $DK = substr($DK, 0, $dkLen);
	        if (!$raw_output) $DK = bin2hex($DK);
	
	        return $DK;
	
	    //derived key too long
	    } else {
	        return false;
	    }
	}
}