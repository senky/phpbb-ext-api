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

class mutation extends type\type
{
	public function __construct(
		\senky\api\graphql\mutator\login $login_mutator,
		\senky\api\graphql\mutator\topic $topic_mutator
	)
	{
		$config = [
			'name'		=> 'Mutation',
			'fields'	=> [
				// login type
				'login' => [
					'type'	=> types::login_result(),
					'args'	=> [
						'username'	=> types::string(),
						'password'	=> types::string(),
					],
					'resolve'	=> [$login_mutator, 'login'],
				],
				// topic type
				'createTopic'	=> [
					'type'	=> types::topic(),
					'args'	=> [
						'forum_id'	=> types::nonNull(types::id()),
						'subject'	=> types::nonNull(types::string()),
						'message'	=> types::nonNull(types::string()),
					],
					'resolve'	=> [$topic_mutator, 'create'],
				],
			],
		];
		parent::__construct($config);
	}
}
