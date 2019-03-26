<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql;

use senky\api\graphql\types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class query extends ObjectType
{
	public function __construct()
	{
		$config = [
			'name'		=> 'Query',
			'fields'	=> [
				'forum'	=> [
					'type'	=> types::forum(),
					'args'	=> [
						'forum_id'	=> types::nonNull(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->forum_buffer->add($args['forum_id'], $fields);

						return new \GraphQL\Deferred(function() use ($args, $context) {
							return $context->forum_buffer->get($args['forum_id']);
						});
					},
				],
				'forums'	=> [
					'type'	=> types::listOf(types::forum()),
					'args'	=> [
						'forum_ids'	=> types::listOf(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->forum_buffer->add_fields($fields);

						if (!empty($args['forum_ids']))
						{
							foreach ($args['forum_ids'] as $forum_id)
							{
								$context->forum_buffer->add($forum_id);
							}
						}

						return new \GraphQL\Deferred(function() use ($context) {
							return $context->forum_buffer->get_all();
						});
					},
				],
				'topic'	=> [
					'type'	=> types::topic(),
					'args'	=> [
						'topic_id'	=> types::id(),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->topic_buffer->add($args['topic_id'], $fields);

						return new \GraphQL\Deferred(function() use ($args, $context) {
							return $context->topic_buffer->get($args['topic_id']);
						});
					},
				],
				'topics'	=> [
					'type'	=> types::listOf(types::topic()),
					'args'	=> [
						'topic_ids'	=> types::listOf(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->topic_buffer->add_fields($fields);

						if (!empty($args['topic_ids']))
						{
							foreach ($args['topic_ids'] as $topic_id)
							{
								$context->topic_buffer->add($topic_id);
							}
						}

						return new \GraphQL\Deferred(function() use ($context) {
							return $context->topic_buffer->get_all();
						});
					},
				],
				'post'	=> [
					'type'	=> types::post(),
					'args'	=> [
						'post_id'	=> types::id(),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->post_buffer->add($args['post_id'], $fields);

						return new \GraphQL\Deferred(function() use ($args, $context) {
							return $context->post_buffer->get($args['post_id']);
						});
					},
				],
				'posts'	=> [
					'type'	=> types::listOf(types::post()),
					'args'	=> [
						'post_ids'	=> types::listOf(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->post_buffer->add_fields($fields);

						if (!empty($args['post_ids']))
						{
							foreach ($args['post_ids'] as $post_id)
							{
								$context->post_buffer->add($post_id);
							}
						}

						return new \GraphQL\Deferred(function() use ($context) {
							return $context->post_buffer->get_all();
						});
					},
				],
				'user'	=> [
					'type'	=> types::user(),
					'args'	=> [
						'user_id'	=> types::id(),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->user_buffer->add($args['user_id'], $fields);

						return new \GraphQL\Deferred(function() use ($args, $context) {
							return $context->user_buffer->get($args['user_id']);
						});
					},
				],
				'users'	=> [
					'type'	=> types::listOf(types::user()),
					'args'	=> [
						'user_ids'	=> types::listOf(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $context->clean_fields($info->getFieldSelection());
						$context->user_buffer->add_fields($fields);

						if (!empty($args['user_ids']))
						{
							foreach ($args['user_ids'] as $user_id)
							{
								$context->user_buffer->add($user_id);
							}
						}

						return new \GraphQL\Deferred(function() use ($context) {
							return $context->user_buffer->get_all();
						});
					},
				],
			],
		];
		parent::__construct($config);
	}
}
