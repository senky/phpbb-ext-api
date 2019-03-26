<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\type;

use senky\api\graphql\type\types;
use GraphQL\Type\Definition\ObjectType;

class user_type extends ObjectType
{
	public function __construct()
	{
		$config = [
			'name'		=> 'User',
			'fields'	=> [
				'user_id'					=> types::id(),
				'user_type'					=> types::int(),
				'group_id'					=> types::id(),
				'user_permissions'			=> types::string(),
				'user_perm_from'			=> types::int(),
				'user_ip'					=> types::string(),
				'user_regdate'				=> types::int(),
				'username'					=> types::string(),
				'username_clean'			=> types::string(),
				'user_password'				=> types::string(),
				'user_passchg'				=> types::int(),
				'user_email'				=> types::string(),
				'user_email_hash'			=> types::int(),
				'user_birthday'				=> types::string(),
				'user_lastvisit'			=> types::int(),
				'user_lastmark'				=> types::int(),
				'user_lastpost_time'		=> types::int(),
				'user_lastpage'				=> types::string(),
				'user_last_confirm_key'		=> types::string(),
				'user_last_search'			=> types::int(),
				'user_warnings'				=> types::int(),
				'user_last_warning'			=> types::int(),
				'user_login_attempts'		=> types::int(),
				'user_inactive_reason'		=> types::int(),
				'user_inactive_time'		=> types::int(),
				'user_posts'				=> types::int(),
				'user_lang'					=> types::string(),
				'user_timezone'				=> types::string(),
				'user_dateformat'			=> types::string(),
				'user_style'				=> types::int(),
				'user_rank'					=> types::int(),
				'user_colour'				=> types::string(),
				'user_new_privmsg'			=> types::int(),
				'user_unread_privmsg'		=> types::int(),
				'user_last_privmsg'			=> types::int(),
				'user_message_rules'		=> types::boolean(),
				'user_full_folder'			=> types::int(),
				'user_emailtime'			=> types::int(),
				'user_topic_show_days'		=> types::int(),
				'user_topic_sortby_type'	=> types::string(),
				'user_topic_sortby_dir'		=> types::string(),
				'user_post_show_days'		=> types::int(),
				'user_post_sortby_type'		=> types::string(),
				'user_post_sortby_dir'		=> types::string(),
				'user_notify'				=> types::boolean(),
				'user_notify_pm'			=> types::boolean(),
				'user_notify_type'			=> types::int(),
				'user_allow_pm'				=> types::boolean(),
				'user_allow_viewonline'		=> types::boolean(),
				'user_allow_viewemail'		=> types::boolean(),
				'user_allow_massemail'		=> types::boolean(),
				'user_options'				=> types::int(),
				'user_avatar'				=> types::string(),
				'user_avatar_type'			=> types::string(),
				'user_avatar_width'			=> types::int(),
				'user_avatar_height'		=> types::int(),
				'user_sig'					=> types::string(),
				'user_sig_bbcode_uid'		=> types::string(),
				'user_sig_bbcode_bitfield'	=> types::string(),
				'user_jabber'				=> types::string(),
				'user_actkey'				=> types::string(),
				'user_newpasswd'			=> types::string(),
				'user_form_salt'			=> types::string(),
				'user_new'					=> types::boolean(),
				'user_reminded'				=> types::int(),
				'user_reminded_time'		=> types::int(),
			],
		];
		parent::__construct($config);
	}
}
