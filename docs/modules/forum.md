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
List topics in a forum, sorted chronologically (newest first).

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

## new
Create a new topic. __You must be authenticated.__

### Data
* username - (string, optional) Either username or user_id of the posting/authenticated user must be specified.
* user_id - (integer, optional) Either username or user_id of the posting/authenticated user must be
* forum_id - (integer)
* topic_title - (string)
* topic_body - (string)


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