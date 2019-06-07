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
use GraphQL\Type\Definition\ResolveInfo;

class post_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Post',
			'needs_buffer'	=> true,
			'fields'		=> function() {
				return [
					'post_id'				=> types::id(),
					'topic_id'				=> types::id(),
					'forum_id'				=> types::id(),
					'poster_id'				=> types::id(),
					'icon_id'				=> types::id(),
					'poster_ip'				=> types::string(),
					'post_time'				=> types::int(),
					'post_reported'			=> types::boolean(),
					'enable_bbcode'			=> types::boolean(),
					'enable_smilies'		=> types::boolean(),
					'enable_magic_url'		=> types::boolean(),
					'enable_sig'			=> types::boolean(),
					'post_username'			=> types::string(),
					'post_subject'			=> types::string(),
					'post_text'				=> types::string(),
					'post_checksum'			=> types::string(),
					'post_attachment'		=> types::boolean(),
					'bbcode_bitfield'		=> types::string(),
					'bbcode_uid'			=> types::string(),
					'post_postcount'		=> types::boolean(),
					'post_edit_time'		=> types::int(),
					'post_edit_reason'		=> types::string(),
					'post_edit_user'		=> types::id(),
					'post_edit_count'		=> types::int(),
					'post_edit_locked'		=> types::boolean(),
					'post_visibility'		=> types::int(),
					'post_delete_time'		=> types::int(),
					'post_delete_reason'	=> types::string(),
					'post_delete_user'		=> types::id(),
	
					// additional fields
					'post_html'	=> [
						'type'				=> types::string(),
						'requires_fields'	=> ['post_text', 'bbcode_uid', 'bbcode_bitfield'],
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							return generate_text_for_display($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], ($row['bbcode_bitfield'] ? OPTION_FLAG_BBCODE : 0) | OPTION_FLAG_SMILIES);
						},
						
					],
					'topic'	=> [
						'type'				=> types::topic(),
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							return $context->resolver->resolve($row, $args, $context, $info);
						},
					],
					'forum'	=> [
						'type'				=> types::forum(),
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							return $context->resolver->resolve($row, $args, $context, $info);
						},
					],
					'poster'	=> [
						'type'				=> types::user(),
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							$info->fieldName = 'user';
							$row['user_id'] = $row['poster_id'];
							return $context->resolver->resolve($row, $args, $context, $info);
						},
					],
				];
			}
		];
		parent::__construct($this->definition);
	}
}
