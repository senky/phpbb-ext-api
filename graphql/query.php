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
	public function __construct(\senky\api\graphql\resolver $resolver)
	{
		$config = [
			'name'		=> 'Query',
			'fields'	=> [
				'forum'	=> [
					'type'	=> types::forum(),
					'args'	=> [
						'forum_id'	=> types::nonNull(types::id()),
					],
				],
				'forums'	=> [
					'type'	=> types::listOf(types::forum()),
					'args'	=> [
						'forum_ids'	=> types::listOf(types::id()),
					],
				],
				'topic'	=> [
					'type'	=> types::topic(),
					'args'	=> [
						'topic_id'	=> types::id(),
					],
				],
				'topics'	=> [
					'type'	=> types::listOf(types::topic()),
					'args'	=> [
						'topic_ids'	=> types::listOf(types::id()),
					],
				],
				'post'	=> [
					'type'	=> types::post(),
					'args'	=> [
						'post_id'	=> types::id(),
					],
				],
				'posts'	=> [
					'type'	=> types::listOf(types::post()),
					'args'	=> [
						'post_ids'	=> types::listOf(types::id()),
					],
				],
				'user'	=> [
					'type'	=> types::user(),
					'args'	=> [
						'user_id'	=> types::id(),
					],
				],
				'users'	=> [
					'type'	=> types::listOf(types::user()),
					'args'	=> [
						'user_ids'	=> types::listOf(types::id()),
					],
				],
			],
			'resolveField'	=> [$resolver, 'resolve'],
		];
		parent::__construct($config);
	}
}
