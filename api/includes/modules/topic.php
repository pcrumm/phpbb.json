<?php

/**
 * Handles actions related to individual topics.
 * @package phpbb.json
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Florin Pavel
 */

namespace phpBBJSON\Module;
class Topic extends \phpBBJSON\Module\Base {
    
	/**
	 * List statistics for a topic
	 * 
	 * Data: topic_id - (integer)
	 * Result(JSON):
	 * - forum_id - (integer)
	 * - total_replies - (integer) 
	 */
    public function info() {
        $db = $this->phpbb->get_db();

        $secret = ($this->request->get_data('secret') != '' && \phpBBJSON\verifySecret($this->request->get_data('secret'))) ? $this->request->get_data('secret') : null;
        $topic_id = ($this->request->get_data('topic_id')) ? $this->request->get_data('topic_id') : null;
        if($topic_id == null) {
            throw new \phpBBJSON\Exception\InternalError("The topic you selected does not exist.");
        }

        $sql = "SELECT topic_replies, forum_id FROM " . TOPICS_TABLE . " WHERE topic_id = {$topic_id}";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $info = array(
            'forum_id' => $row['forum_id'],
            'total_replies' => $row['topic_replies']
        );
        $this->response->set_header(HTTP_VALID);
        $this->response->set_data($info);
        $this->response->response();
    }

    /**
     * List all posts in a topic, sorted chronologically (oldest first).
     * 
     */
    
    public function postList() {
        $db = $this->phpbb->get_db();
        $user = $this->phpbb->get_user();
        $auth = $this->phpbb->get_auth();
        $config = $this->phpbb->get_config();

        $results = array();

        $topic_id = ($this->request->get_data('topic_id')) ? $this->request->get_data('topic_id') : null;
        if($topic_id == null) {
            throw new \phpBBJSON\Exception\InternalError("The topic you selected does not exist.");
        }

        $secret = ($this->request->get_data('secret') != '' && \phpBBJSON\verifySecret($this->request->get_data('secret'))) ? $this->request->get_data('secret') : null;
        $user_id = null;
        if ($secret != null) {
            $user_id = \phpBBJSON\getIdFromSecret($secret);
            $userdata = \phpBBJSON\userdata($user_id);
        } else {
            $user->session_begin();
            $userdata = $user->data;
            $user_id = $userdata['user_id'];
        }
        $auth->acl($userdata);
			
        // get forum id and topic title
        $obj = $db->sql_fetchrow($db->sql_query("SELECT forum_id, topic_title FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id));
        $forum_id = $obj['forum_id'];
        $topic_title = $obj['topic_title'];
		
        // get forum title
        $obj = $db->sql_fetchrow($db->sql_query("SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = " . $forum_id));
        $forum_title = $obj['forum_name'];
        
        // get topic posts
        $sql = "
        	SELECT 
        		post_id, 
        		post_time, 
        		" . USERS_TABLE . ".username, 
        		" . USERS_TABLE . ".user_id
        	FROM " . POSTS_TABLE . "
        	LEFT OUTER JOIN " . USERS_TABLE . " ON " . USERS_TABLE . ".user_id = " . POSTS_TABLE . ".poster_id
        	ORDER BY post_time ASC		
        ";
        
        $query = $db->sql_query($sql);
        $results = array(
        	'forum_id' => $forum_id,
        	'forum_name' => $forum_title,
        	'topic_title' => $topic_title
        );
        
        while($row = $db->sql_fetchrow($query)) {
        	$results['posts'][] = array(
        		'post_id' => $row['post_id'],
        		'author_id' => $row['user_id'],
        		'author_username' => $row['username'],
        		'timestamp' => $row['post_time']
        	);
        }
        
        $this->response->set_header(HTTP_VALID);
        $this->response->set_data($results);
        $this->response->response();
    }

    /**
     * Get the currently authenticated user's permissions. User must be authenticated.
     * @throws \phpBBJSON\Exception\InternalError
     */
    public function permissions() {
        $db = $this->phpbb->get_db();
        $user = $this->phpbb->get_user();
        $auth = $this->phpbb->get_auth();

        $topic_id = ($this->request->get_data('topic_id')) ? $this->request->get_data('topic_id') : null;
        if($topic_id == null) {
            throw new \phpBBJSON\Exception\InternalError("The topic you selected does not exist.");
        }

        $secret = ($this->request->get_data('secret') != '' && \phpBBJSON\verifySecret($this->request->get_data('secret'))) ? $this->request->get_data('secret') : null;
        $user_id = null;
        if ($secret != null) {
            $user_id = \phpBBJSON\getIdFromSecret($secret);
            $userdata = \phpBBJSON\userdata($user_id);
        } else {
            $user->session_begin();
            $userdata = $user->data;
        }
        $auth->acl($userdata);

        $obj = $db->sql_fetchrow($db->sql_query("SELECT forum_id FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id));
        $forum_id = $obj['forum_id'];

        $permissions = array(
            'can_see' => ($auth->acl_get('f_list', $forum_id)) ? true : false,
            'can_read' => ($auth->acl_get('f_read', $forum_id)) ? true : false,
            'can_post' => ($auth->acl_get('f_post', $forum_id)) ? true : false,
            'can_reply' => ($auth->acl_get('f_reply', $forum_id)) ? true : false
        );

        $this->response->set_header(HTTP_VALID);
        $this->response->set_data($permissions);
        $this->response->response();
    }

    /**
     * Post a reply to a topic. User must be authenticated
     * 
     */
    public function reply() {
        global $phpEx, $phpbb_root_path;
        include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
        include($phpbb_root_path . 'includes/message_parser.' . $phpEx);
        
        $db = $this->phpbb->get_db();
        $user = $this->phpbb->get_user();
        $auth = $this->phpbb->get_auth();
        $config = $this->phpbb->get_config();

        $topic_id = ($this->request->get_data('topic_id')) ? $this->request->get_data('topic_id') : null;
        if($topic_id == null) {
            throw new \phpBBJSON\Exception\InternalError("The topic you selected does not exist.");
        }
        $obj = $db->sql_fetchrow($db->sql_query("SELECT forum_id, topic_title FROM " . TOPICS_TABLE . " WHERE topic_id = " . $topic_id));
        $forum_id = $obj['forum_id'];

        $secret = ($this->request->get_data('secret') != '' && \phpBBJSON\verifySecret($this->request->get_data('secret'))) ? $this->request->get_data('secret') : null;
        $user_id = null;

        if($secret != null) {
            $user_id = \phpBBJSON\getIdFromSecret($secret);
            $userdata = \phpBBJSON\userdata($user_id);
            $auth->acl($userdata);
            $user->session_begin();
            $user->data = array_merge($user->data, $userdata);

            if($auth->acl_get('f_post', $forum_id)) {
                $uid = $bitfield = $flags = '';
                $message = $this->request->get_data('topic_body');
                $subject = "Re: " . $obj['topic_title'];
                generate_text_for_storage($message, $uid, $bitfield, $flags, TRUE, TRUE, TRUE);

                $data = array(
                    // General Posting Settings
                    'forum_id' => $forum_id, // The forum ID in which the post will be placed. (int)
                    'topic_id' => $topic_id, // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
                    'icon_id' => false, // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)
                    // Defining Post Options
                    'enable_bbcode' => true, // Enable BBcode in this post. (bool)
                    'enable_smilies' => true, // Enabe smilies in this post. (bool)
                    'enable_urls' => true, // Enable self-parsing URL links in this post. (bool)
                    'enable_sig' => true, // Enable the signature of the poster to be displayed in the post. (bool)
                    // Message Body
                    'message' => $message, // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
                    'message_md5' => md5($message), // The md5 hash of your message
                    // Values from generate_text_for_storage()
                    'bbcode_bitfield' => $bitfield, // Value created from the generate_text_for_storage() function.
                    'bbcode_uid' => $uid, // Value created from the generate_text_for_storage() function.
                    // Other Options
                    'post_edit_locked' => 0, // Disallow post editing? 1 = Yes, 0 = No
                    'topic_title' => $subject, // Subject/Title of the topic. (string)
                    // Email Notification Settings
                    'notify_set' => false, // (bool)
                    'notify' => false, // (bool)
                    'post_time' => 0, // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
                    'forum_name' => '', // For identifying the name of the forum in a notification email. (string)
                    // Indexing
                    'enable_indexing' => true, // Allow indexing the post? (bool)
                    // 3.0.6
                    'force_approved_state' => true, // Allow the post to be submitted without going into unapproved queue
                    // 3.1-dev, overwrites force_approve_state
                    'force_visibility' => true, // Allow the post to be submitted without going into unapproved queue, or make it be deleted
                    'topic_first_poster_colour' => $userdata['user_colour']
                );

                $poll = array();
                $result = submit_post('reply', $subject, $userdata['username'], POST_NORMAL, $poll, $data);
				preg_match("/p=\d(.*?)\b/", $result, $matches);
				$post_id = explode('=', $matches[0]);
				$post_id = $post_id[1];
				
                $this->response->set_header(HTTP_VALID);
                $this->response->set_data(array(
                    'post_id' => $post_id
                ));
                $this->response->response();
            } else {
            	throw new \phpBBJSON\Exception\Unauthorized("You do not have necessary permissions to post in this topic!");
            } 
        } else {
        	throw new \phpBBJSON\Exception\Unauthorized('You must be logged in to post in this topic!');
        }
    }

}