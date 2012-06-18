#forum
The *forum* module handles actions related to individual forums.

## info
Lists statistics for a forum.

### Data
* forum_id - (integer)

### Result
* total_topics
* total_posts
* total_replies

## list
List topics in a forum, sorted chronologically.

### Data
* forum_id - (integer) The numeric ID of the forum
* per_page - (integer, optional: defaults to board setting) The number of topics to return
* page - (integer, optional: defaults to 1) the page to display. Uses the per_page setting to determine offset

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


## mark
Meta actions: mark the entirety of a forum read or unread.

### Data
* forum_id - (integer)
* mark_read - (boolean, optional) - You must specify either mark_read or mark_unread
* mark_unread - (boolean, optional) - You must specify either mark_read or mark_unread

### Result
* forum_id - (integer) Returned for confirmation purposes