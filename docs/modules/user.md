#user
The _user_ module handles actions related to individual users.

## info
### data
* user_id - (integer, optional) user_id or username must be specified.
* username - (string, optional) user_id or username must be specified.

### Result
* username - (string)
* location - (string)
* num_posts - (integer)
* last_login - (integer) UTC UNIX timestamp
* rank - (string)