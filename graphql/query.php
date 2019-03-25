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
						'id'	=> types::nonNull(types::id()),
					],
					'resolve'	=> function($db, $args, $context, ResolveInfo $info) {
						$fields = array_keys($info->getFieldSelection());

						$sql = 'SELECT ' . implode(',', $fields) . '
							FROM ' . FORUMS_TABLE . '
							WHERE forum_id = ' . (int) $args['id'];
						$result = $db->sql_query($sql);
						$row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);
						return $row;
					},
				],
				'forums'	=> [
					'type'	=> types::listOf(types::forum()),
					'resolve'	=> function($db, $args, $context, ResolveInfo $info) {
						$fields = array_keys($info->getFieldSelection());

						$sql = 'SELECT ' . implode(',', $fields) . '
							FROM ' . FORUMS_TABLE;
						$result = $db->sql_query($sql);
						$rows = $db->sql_fetchrowset($result);
						$db->sql_freeresult($result);
						return $rows;
					},
				],
			],
		];
		parent::__construct($config);
	}
}
