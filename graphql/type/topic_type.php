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

class topic_type extends ObjectType
{
	public function __construct()
	{
		$config = [
			'name'		=> 'Topic',
			'fields'	=> [
				'topic_id'					=> types::id(),
				'forum_id'					=> types::id(),
				'icon_id'					=> types::id(),
				'topic_attachment'			=> types::boolean(),
				'topic_reported'			=> types::boolean(),
				'topic_title'				=> types::string(),
				'topic_poster'				=> types::int(),
				'topic_time'				=> types::int(),
				'topic_time_limit'			=> types::int(),
				'topic_views'				=> types::int(),
				'topic_status'				=> types::int(),
				'topic_type'				=> types::int(),
				'topic_first_post_id'		=> types::id(),
				'topic_first_poster_name'	=> types::string(),
				'topic_first_poster_colour'	=> types::string(),
				'topic_last_post_id'		=> types::id(),
				'topic_last_poster_id'		=> types::id(),
				'topic_last_poster_name'	=> types::string(),
				'topic_last_poster_colour'	=> types::string(),
				'topic_last_post_subject'	=> types::string(),
				'topic_last_post_time'		=> types::int(),
				'topic_last_view_time'		=> types::int(),
				'topic_moved_id'			=> types::int(),
				'topic_bumped'				=> types::boolean(),
				'topic_bumper'				=> types::int(),
				'poll_title'				=> types::string(),
				'poll_start'				=> types::int(),
				'poll_length'				=> types::int(),
				'poll_max_options'			=> types::int(),
				'poll_last_vote'			=> types::int(),
				'poll_vote_change'			=> types::boolean(),
				'topic_visibility'			=> types::int(),
				'topic_delete_time'			=> types::int(),
				'topic_delete_reason'		=> types::string(),
				'topic_delete_user'			=> types::id(),
				'topic_posts_approved'		=> types::int(),
				'topic_posts_unapproved'	=> types::int(),
				'topic_posts_softdeleted'	=> types::int(),

				// additional fields
				'forum'	=> [
					'type'	=> types::forum(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
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
