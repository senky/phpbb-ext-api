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
use GraphQL\Type\Definition\ResolveInfo;

class statistics_type extends ObjectType
{
	public function __construct()
	{
		$config = [
			'name'			=> 'Statistics',
			'needs_buffer'	=> false,
			'fields'		=> [
				'total_posts'	=> [
					'type'		=> types::int(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						return (int) $context->config['num_posts'];
					},
				],
				'total_topics'	=> [
					'type'		=> types::int(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						return (int) $context->config['num_topics'];
					},
				],
				'total_users'	=> [
					'type'		=> types::int(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						return (int) $context->config['num_users'];
					},
				],
				'newest_user'	=> [
					'type'		=> types::user(),
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) {
						$args['user_id'] = $context->config['newest_user_id'];
						return $context->resolver->resolve($row, $args, $context, $info);
					},
				],
			],
		];
		parent::__construct($config);
	}
}
