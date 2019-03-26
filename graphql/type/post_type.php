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

use senky\api\graphql\types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class post_type extends ObjectType
{
	public function __construct()
	{
		$config = [
			'name'		=> 'Post',
			'fields'	=> [
				'post_id'			=> types::id(),
				'topic_id'			=> types::id(),
				'forum_id'			=> types::id(),
				'poster_id'			=> types::id(),
				'icon_id'			=> types::id(),
				'poster_ip'			=> types::string(),
				'post_time'			=> types::int(),
				'post_approved'		=> types::boolean(),
				'post_reported'		=> types::boolean(),
				'enable_bbcode'		=> types::boolean(),
				'enable_smilies'	=> types::boolean(),
				'enable_magic_url'	=> types::boolean(),
				'enable_sig'		=> types::boolean(),
				'post_username'		=> types::string(),
				'post_subject'		=> types::string(),
				'post_text'			=> types::string(),
				'post_checksum'		=> types::string(),
				'post_attachment'	=> types::boolean(),
				'bbcode_bitfield'	=> types::string(),
				'bbcode_uid'		=> types::string(),
				'post_postcount'	=> types::boolean(),
				'post_edit_time'	=> types::int(),
				'post_edit_reason'	=> types::string(),
				'post_edit_user'	=> types::int(),
				'post_edit_count'	=> types::int(),
				'post_edit_locked'	=> types::boolean(),

				// additional fields
				'topic'	=> [
					'type'	=> types::topic(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						$fields = array_keys($info->getFieldSelection());
						$context->topic_buffer->add($row['topic_id'], $fields);

						return new \GraphQL\Deferred(function() use ($row, $context) {
							return $context->topic_buffer->get($row['topic_id']);
						});
					},
				],
				'forum'	=> [
					'type'	=> types::forum(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						$fields = array_keys($info->getFieldSelection());
						$context->forum_buffer->add($row['forum_id'], $fields);

						return new \GraphQL\Deferred(function() use ($row, $context) {
							return $context->forum_buffer->get($row['forum_id']);
						});
					},
				],
			],
		];
		parent::__construct($config);
	}
}
