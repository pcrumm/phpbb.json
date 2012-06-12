# phpbb.json
### JSON API for phpBB forums

## Requesting Data
All communications with the phpbb.json occur over HTTP(s), preferably via HTTPS if available. The API will, by convention, be placed in an `api/` directory inside of the forum's root directory.

### Request Format
An API request is composed of three pieces of information, the _module_, _interface_, and _data_. The _module_ describes the top-level API member, for instance, "auth" for authentication and "topic" for topic data.

#### Specifying Module and Interface
The _module_ and _interface_ are specified via the URL:

	http://example.com/phpbb3/module/interface
Example: Module: auth, Interface: login:

	http://example.com/phpbb3/auth/login
A trailing slash is optional following the interface specification.

#### Specifying Data
All data elements shall be encoded using JSON and submitted, via GET or POST, in this format, as the _data_ field. A sample request to `module/interface` may, for instance, utilize the following data array:

	$my_data = array(
		'username'	=> 'phil',
		'realname'	=> 'Phil Crumm',
	);
We JSON encode `$my_data` (we can use PHP's `json_encode()` function for this), and produce the following:

	{"username":"phil","realname":"Phil Crumm"}
	
We then submit this data, using the _data_ field of the request. We URL encode the contents of our request data.

	curl --data-urlencode 'data={"username":"phil","realname":"Phil Crumm"}' http://example.com/phpbb3/modue/interface
	
Note that, though we can submit this data via POST or GET, convention favors POST; this prevents the filling of logs with extraneous information, and the potential disclosure of sensitive information (e.g. passwords) in these logs.

If there is no data to be sent, an empty JSON array (`{}`) should be sent.

## Receiving Data
### Headers
phpbb.json will follow standard HTTP status code conventions when replying to requests.

* 200 - Request OK - Request valid and executed, reply sent
* 400 - Bad Request - Your request was not formatted correctly (i.e. invalid/no JSON received)
* 401 - Unauthorized - You are not authenticated as a user with permission to carry out the request
* 501 - Not Implemented - The requested module or interface does not exist


### Data
phpbb.json will, in addition to the header specified above, reply with a JSON encoded string containing the requested data, as necessary. Individual API methods specify their unique return data.