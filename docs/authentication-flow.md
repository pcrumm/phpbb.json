# Authentication Flow
The vast majority of phpBB installations run on shared web hosts. This, unfortunately, means that most API providers will be unable to serve the API secured via SSL. As a consequence, we are forced to send all communications over plaintext. We therefore implement an unfortunately verbose request authentication mechanism to verify the authenticity of each request and prevent some common attack vectors.

### Non-authenticated actions
Some actions (e.g. viewing a forum list or topics in a forum) may not require authentication. If this is the case, you need not include any non-data portions of the request.

### A note about SSL usage
If you are able to enable SSL for your board (or, at minimum, for the `api/` directory), we encourage you to do so. Requests will take an identical format to their non-SSL counterparts; any special considerations must be handled by the client.

# Request Components
## data
This is the main data for the request. It is a urlencode()-ed JSON array containing the information required by the individual interface to operate. Each interface's documentation specifies

## timestamp
A UTC UNIX timestamp generated at the time of request generation that represents the request. Timestamps older than **60 seconds** will be rejected by the API server.

## username
The user's urlencode()-ed username on the board. This is used as a unique identifier for the user.

## hash
A HMAC-SHA256 hash (this can be generated using PHP's [hash_hmac()](http://php.net/manual/en/function.hash-hmac.php) function) of the following format:

	timestamp-username-data
The _secret_ is a PBKDF2 (SHA-256) key of 1,000 iterations of the user's _plaintext_ password salted with the user's username. It can be generated via [this community-contributed function](http://www.php.net/manual/en/function.hash-hmac.php#108966) in PHP.

# Creating a Sample Request
This is all a little complicated, so let's break it down a little. Let's imagine I want to submit an authenticated request to _module_/_interface_. Let's also imagine that my username is "phil", and my password is "foobar". I want to submit the following data in my request:

	$request_data = array(
	        'foo'   => 'bar',
	        'bar'   => 'foo',
	        'why'   => 'because',
	);
First, we json_encode our data. We get

	{"foo":"bar","bar":"foo","why":"because"}
Now, we urlencode() it

	%7B%22foo%22%3A%22bar%22%2C%22bar%22%3A%22foo%22%2C%22why%22%3A%22because%22%7Ds
Ugly? You bet, but it'll get the job done.

Now, we need to sign our request. We'll first generate the secret; we do this using the [pbkdf2() function](http://www.php.net/manual/en/function.hash-hmac.php#108966) above.

	<?php
	// … pbkdf2 implementation …
	$username = 'phil';
	$password = 'foobar';
	$secret = pbkdf2('sha256', $password, $username, 1000);
We'll get this as our secret:

	9cd9bead0d3d6238476971ac0a445ff799729d92b55b56ae8961fd9e4c22c2ed
We use this and sign our request using the format above:

	$hash = hash_hmac('sha256', $time . '-' . $username . '-' . $data, $secret);
Finally, we'll submit the full request to the server in the manner of our choice. Here's an example using the command line version of cURL:

	curl -d "data=%7B%22foo%22%3A%22bar%22%2C%22bar%22%3A%22foo%22%2C%22why%22%3A%22because%22%7Ds&username=phil&hash=187aa2cc4e4e95e782cfdccdd8264284f07c793485af0a974b86a601e48a000d&timestamp=1339472956" http://example.com/module/interface
	
You'll notice the four distinct request components: username, hash, timestamp, and data.