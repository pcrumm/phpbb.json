# private-message
The _private-message_ module handles actions related to the private message system, and also presents some statistics. Note that all interfaces within this module **require authentication**. See authentication-flow for more information on this process.

## delete
Deletes the specified message.

### data
* message_id - (integer) The ID of the message to sent.

### Result
* message_id - (integer) For confirmation purposes, the ID of the just-deleted message.


## folders
Lists all of the user's private message folders.

### data
No data is required.

### Result
A two-dimensional JSON array (sorted by system folders--Inbox, Sent, etc. first, then user folders (alphabetically)) with the following fields:

* folder_id - (string or integer) The folder's internal ID. Built-in folders use strings (e.g. Inbox => "inbox"), user added folders use integers.
* folder_name - (string) The folder's display name
* total_messages - (integer) The number of messages in the folder
* unread_messages - (integer) The number of unread messages in the folder
* capacity - (integer) The administrator defined folder message limit

## list
Lists all of the messages currently in the specified folder. If no folder is specified, we default to inbox.

### data
* folder_id - (integer, optional) The ID of the folder (obtained from the _folders_ interface) to list. If none specified, messages from the inbox are displayed.

		curl --data-urlencode 'data={"folder_id":13}' http://example.com/phpbb/api/private-message/list
		curl --data-urlencode 'data={}' http://example.com/phpbb/api/private-message/list


### Result
A two-dimensional JSON array (sorted by message time, descending) representing the folder. The following data is sent:

* message_id - (integer) The message's numeric identifier
* message_subject - (string)
* message_timestamp - (integer) UTC UNIX timestamp that marks the message's sent time
* message_unread - (boolean) True if the message is unread
* message_author_name - (string) Message author's username
* message_author_id - (integer) Message author's numeric ID



## send
Sends a message to the specified recipients.

### data
* message_recipient - (integer) The user ID of the desired recipient
* message_subject - (string)
* message_body - (string)

### Result
* sent_time - (integer) The UTC UNIX timestamp of the message submission. *Note:* This data is merely used to confirm that the message was properly sent, and is not particularly useful in and of itself.

## statistics
Retrieves private message statistics for the current user (total messages, unread messages, etc.)

### data
* username - (string) The user's username -OR-
* user_id - (id) The user's numeric user ID

		curl --data-urlencode 'data={"username":"phil"}' http://example.com/phpbb/api/private-message/statistics
		curl --data-urlencode 'data={"user_id":42}' http://example.com/phpbb/api/private-message/statistics


### Result
A one-dimensional JSON array containing the following information:

* total_messages - (integer) The user's total message count
* unread_message - (integer) The user's unread message count (note that this uses phpBB's unread counting, which is more like "unseen")
* outbox_messages - (integer) Total count of user's outbox messages (phpBB's definition here is "sent but not read by any party")
* read_messages - (integer) Total count of read messages (phpBB's definition here is "sent and read by any party")

		{"total_messages":1000,"unread_messages":13,"outbox_messages":0,"read_messages":250}


### Note
The username/user_id specified in the _data_ provided must match the authenticated user or else the request will be refused.

## view
View a particular message

### data
* message_id - (integer) The ID of the message to view

### Result
* message_subject - (string)
* message_timestamp - (integer) UTC UNIX timestamp that marks the message's sent time
* message_unread - (boolean) True if the message is unread
* message_author_name - (string) Message author's username
* message_author_id - (integer) Message author's numeric ID
* message_body - (string) The message's contents

