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
	public function __construct($container)
	{
		$config = [
			'name'		=> 'Query',
			'fields'	=> [
				// forum type
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

				// group type
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

				// icon type
				'icons'	=> [
					'type'	=> types::listOf(types::icon()),
					'args'	=> [
						'icon_ids'	=> types::listOf(types::id()),
					],
				],

				// post type
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

				// rank type
				'ranks'	=> [
					'type'	=> types::listOf(types::rank()),
					'args'	=> [
						'rank_ids'	=> types::listOf(types::id()),
					],
				],

				// search type
				'search_posts'	=> [
					'type'	=> types::listOf(types::post()),
					'args'	=> [
						'keywords'	=> types::string(),
					],
					'resolve'	=> [$container->get('senky.api.graphql.resolver.search'), 'resolve'],
				],
				'search_topics'	=> [
					'type'	=> types::listOf(types::topic()),
					'args'	=> [
						'keywords'	=> types::string(),
					],
					'resolve'	=> [$container->get('senky.api.graphql.resolver.search'), 'resolve'],
				],

				// smiley type
				'smilies'	=> [
					'type'	=> types::listOf(types::smilie()),
					'args'	=> [
						'smilie_ids'	=> types::listOf(types::id()),
					],
				],

				// topic type
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

				// user type
				'user'	=> [
					'type'	=> types::user(),
					'args'	=> [
						'user_id'	=> [
							'type'			=> types::id(),
							'defaultValue'	=> (int) $container->get('user')->data['user_id'],
						],
					],
				],
				'users'	=> [
					'type'	=> types::listOf(types::user()),
					'args'	=> [
						'user_ids'	=> types::listOf(types::id()),
						'start'		=> [
							'type'			=> types::int(),
							'defaultValue'	=> 0,
						],
					],
				],

				// special types
				'statistics'	=> types::statistics(),
			],
			// resolve using buffer by default
			'resolveField'	=> [$container->get('senky.api.graphql.resolver.buffer'), 'resolve'],
		];
		parent::__construct($config);
	}
}
