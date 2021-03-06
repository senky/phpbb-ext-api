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

class forum_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Forum',
			'needs_buffer'	=> true,
			'fields'		=> function() {
				return [
					'forum_id'					=> types::id(),
					'parent_id'					=> types::id(),
					'left_id'					=> types::id(),
					'right_id'					=> types::id(),
					'forum_parents'				=> types::string(),
					'forum_name'				=> types::string(),
					'forum_desc'				=> types::string(),
					'forum_desc_options'		=> types::int(),
					'forum_link'				=> types::string(),
					'forum_password'			=> types::string(),
					'forum_style'				=> types::int(),
					'forum_image'				=> types::string(),
					'forum_rules'				=> types::string(),
					'forum_rules_link'			=> types::string(),
					'forum_rules_options'		=> types::int(),
					'forum_topics_per_page'		=> types::int(),
					'forum_type'				=> types::int(),
					'forum_status'				=> types::int(),
					'forum_last_post_id'		=> types::id(),
					'forum_last_poster_id'		=> types::id(),
					'forum_last_post_subject'	=> types::string(),
					'forum_last_post_time'		=> types::int(),
					'forum_last_poster_name'	=> types::string(),
					'forum_last_poster_colour'	=> types::string(),
					'forum_flags'				=> types::int(),
					'display_on_index'			=> types::boolean(),
					'enable_indexing'			=> types::boolean(),
					'enable_icons'				=> types::boolean(),
					'enable_prune'				=> types::boolean(),
					'prune_next'				=> types::int(),
					'prune_days'				=> types::int(),
					'prune_viewed'				=> types::int(),
					'prune_freq'				=> types::int(),
					'display_subforum_list'		=> types::boolean(),
					'forum_options'				=> types::int(),
					'enable_shadow_prune'		=> types::boolean(),
					'prune_shadow_days'			=> types::int(),
					'prune_shadow_freq'			=> types::int(),
					'prune_shadow_next'			=> types::int(),
					'forum_posts_approved'		=> types::int(),
					'forum_posts_unapproved'	=> types::int(),
					'forum_posts_softdeleted'	=> types::int(),
					'forum_topics_approved'		=> types::int(),
					'forum_topics_unapproved'	=> types::int(),
					'forum_topics_softdeleted'	=> types::int(),

					// additional fields
					'parent'	=> [
						'type'				=> types::forum(),
						'requires_fields'	=> ['parent_id'],
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							$info->fieldName = 'forum';
							$row['forum_id'] = $row['parent_id'];
							return $context->buffer_resolver->resolve($row, $args, $context, $info);
						},
					],
					'last_post'	=> [
						'type'				=> types::post(),
						'requires_fields'	=> ['forum_last_post_id'],
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							$info->fieldName = 'post';
							$row['post_id'] = $row['forum_last_post_id'];
							return $context->buffer_resolver->resolve($row, $args, $context, $info);
						},
					],
					'topics'	=> [
						'type'		=> types::listOf(types::topic()),
						'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
							return $context->buffer_resolver->resolve($row, $args, $context, $info);
						},
					],
					'has_unread_posts'	=> [
						'type'				=> types::boolean(),
						'needs_addition'	=> ['unread_posts'],
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							return false;
						},
					],
				];
			},
		];
		parent::__construct($this->definition);
	}
}
