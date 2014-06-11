#forum
The *forum* module handles actions related to individual forums.


## info
Lists statistics for a forum.

### Data
* forum_id - (integer)
    

		curl --data-urlencode 'data={"forum_id":12}' http://example.com/phpbb/api/forum/info


### Result
* total_topics
* total_posts
* total_replies

## list
List topics in a forum, sorted chronologically (newest first).

### Data
* forum_id - (integer) The numeric ID of the forum
* per_page - (integer, optional: defaults to board setting) The number of topics to return
* page - (integer, optional: defaults to 1) the page to display. Uses the per_page setting to determine offset


		curl --data-urlencode 'data={"forum_id":12}' http://example.com/phpbb/api/forum/topicList
		curl --data-urlencode 'data={"forum_id":12,"per_page":10,"page": 4}' http://example.com/phpbb/api/forum/topicList
		
### Result
A two-dimensional JSON array containing topic data:

* topic_id - (integer) The numeric ID of the topic
* topic_title - (string)
* topic_author_username - (string)
* topic_author_id - (integer)
* topic_time - (integer)
* topic_last_reply_username - (string)
* topic_last_reply_id - (integer)
* topic_last_reply_time - (integer)
* topic_num_replies - (integer)
* topic_unread - (boolean)
* topic_locked - (boolean)
* topic_status - (string) Indicates special topic status, e.g. "locked", "shadow" (shadow topic for a moved topic)


## mark(isn't implemented)
Meta actions: mark the entirety of a forum read or unread.

### Data
* forum_id - (integer)
* mark_read - (boolean, optional) - You must specify either mark_read or mark_unread
* mark_unread - (boolean, optional) - You must specify either mark_read or mark_unread

### Result
* forum_id - (integer) Returned for confirmation purposes

## new
Create a new topic. __You must be authenticated.__

### Data
* secret - (string) Is a hash who is generated after user is logged
* forum_id - (integer)
* topic_title - (string)
* topic_body - (string)


		curl --data-urlencode 'data={"secret":"7efcbd2bda7a1a59dfa3e4422a4ae3a05094da3be662ce62","forum_id":2,"topic_title":"A test topic","topic_body":"A test topic"}' http://example.com/phpbb/api/forum/newTopic

### Result
* topic_id - (integer) The ID of the newly created topic

## permissions
Get the currently authenticated user's permissions. __You must be authenticated.__

### Data
* forum_id - (integer)

### Result
* can_see - (boolean) Can see the forum
* can_read - (boolean) Can read the forum
* can_post - (boolean) Can post topics to the forum
* can_reply - (boolean) Can reply to topics in the forum
