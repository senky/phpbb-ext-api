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

use senky\api\graphql\type\types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class query extends ObjectType
{
	public function __construct(\senky\api\graphql\resolver $resolver, \phpbb\user $user)
	{
		$config = [
			'name'		=> 'Query',
			'fields'	=> [
				// forum types
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

				// group types
				'group'	=> [
					'type'	=> types::group(),
					'args'	=> [
						'group_id'	=> types::id(),
					],
				],
				'groups'	=> [
					'type'	=> types::listOf(types::group()),
					'args'	=> [
						'group_ids'	=> types::listOf(types::id()),
					],
				],

				// post types
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
						'topic_id'	=> types::id(),
					],
				],

				// topic types
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
						'forum_id'	=> types::id(),
					],
				],

				// user types
				'user'	=> [
					'type'	=> types::user(),
					'args'	=> [
						'user_id'	=> [
							'type'			=> types::id(),
							'defaultValue'	=> (int) $user->data['user_id'],
						],
					],
				],
				'users'	=> [
					'type'	=> types::listOf(types::user()),
					'args'	=> [
						'user_ids'	=> types::listOf(types::id()),
					],
				],

				// special types
				'statistics'	=> types::statistics(),
			],
			'resolveField'	=> [$resolver, 'resolve'],
		];
		parent::__construct($config);
	}
}
