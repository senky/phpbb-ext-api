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
	public function __construct(\senky\api\graphql\resolver $resolver, $container)
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

				// icon type
				'icons'	=> [
					'type'	=> types::listOf(types::icon()),
					'args'	=> [
						'icon_ids'	=> types::listOf(types::id()),
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

				// rank type
				'ranks'	=> [
					'type'	=> types::listOf(types::rank()),
					'args'	=> [
						'rank_ids'	=> types::listOf(types::id()),
					],
				],

				// search type
				'search'	=> [
					'type'	=> types::listOf(types::post()),
					'args'	=> [
						'keywords'	=> types::string(),
					],
					// search is resolved in a very special way
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) use ($container) {
						$search_type = $container->get('config')['search_type'];
						$error = false;
						$search = new $search_type($error, $container->getParameter('core.root_path'), $container->getParameter('core.php_ext'), $container->get('auth'), $container->get('config'), $container->get('dbal.conn'), $container->get('user'), $container->get('dispatcher'));

						$search->split_keywords($args['keywords'], 'all');
						if ($search->get_search_query())
						{
							$id_ary = [];
							$start = 0;
							$search->keyword_search('posts', 'all', 'all', ['t' => 'p.post_time'], 't', 'd', 0, [], $container->get('content.visibility')->get_global_visibility_sql('post', [], 'p.'), 0, [], '', $id_ary, $start, $container->get('config')['posts_per_page']);

							// we always resolve to only one type
							$info->fieldName = 'posts';
							$row['post_ids'] = $id_ary;
							return $context->resolver->resolve($row, $args, $context, $info);
						}
						return [];
					}
				],

				// smiley type
				'smilies'	=> [
					'type'	=> types::listOf(types::smilie()),
					'args'	=> [
						'smilie_ids'	=> types::listOf(types::id()),
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
			'resolveField'	=> [$resolver, 'resolve'],
		];
		parent::__construct($config);
	}
}
