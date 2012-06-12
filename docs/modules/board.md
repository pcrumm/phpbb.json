#board
The _board_ module handles requests concerning the "root" of the phpBB installation--statistics, the forum list, etc.

## list
Lists visible forums and some pertinent information for each forum. If authentication is supplied, the forum list will consist of the forums the given user is allowed to see. If no authentication is supplied, only guest-visible forums will be display.

### data
* parent_id (integer, optional) - The parent forum for returned forums. Defaults to 0 (all forums displayed).

		curl --data-urlencode "data={}" http://example.com/phpbb/api/board/list
		curl --data-urlencode 'data={"parent_id": "43"}' http://example.com/phpbb/api/board/list

### Result
A two-dimensional JSON array is returned. Each second-dimension represents a forum, and contains the following keys:

* forum_id - (integer) The numeric ID for the forum
* forum_name - (string) The forum's display name
* unread - (bool)
* total_topics - (integer)
* total_posts - (integer)
* last_poster_id - (integer) The last poster's user ID
* last_poster_name - (string) The last poster's display name
* last_post_topic_id - (integer) The last post's parent topic
* last_post_topic_name - (string) The last post's parent topic name
* last_post_time - (integer) UTC UNIX timestamp for last post

		[{"forum_id":1,"forum_name":"A Test Forum","unread":true,"total_topics":33,"total_posts":37,"last_poster_id":1,"last_poster_name":"phil","last_post_topic_id":13,"last_post_topic_name":"Hello, World!","last_post_time":1339535882},{"forum_id":2,"forum_name":"Another Test Forum","unread":false,"total_topics":0,"total_posts":0,"last_poster_id":"","last_poster_name":"","last_post_topic_id":"","last_post_topic_name":"","last_post_time":""}]