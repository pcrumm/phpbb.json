#topic
The _topic_ module handles actions related to topics.

## info
List statistics for a topic.

### Data
* topic_id - (integer)

### Result
* forum_id - (integer)
* total_replies - (integer)
* unread - (boolean)
* unread_replies - (integer)

## list
List all posts in a topic, sorted chronologically (oldest first).

### Data
* topic_id - (integer)

### Result
* forum_id - (integer)
* forum_name - (string)
* topic_title - (string)
* posts (second dimension array):
	* post_id - (integer)
	* author_id - (integer)
	* author_username - (string)
	* timestamp - (integer) UTC UNIX timestamp
	* unread - (boolean)

## permissions
Get the currently authenticated user's permissions. __You must be authenticated.__

### Data
* topic_id - (integer)

### Result
* can_see - (boolean)
* can_read - (boolean)
* can_reply - (boolean)

## reply
Post a reply to a topic. __You must be authenticated.__

### Data
* topic_id - (integer)
* reply_body - (integer)

### Result
* post_id - (integer) The ID of the new post