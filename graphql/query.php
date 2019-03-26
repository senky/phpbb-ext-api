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
						$fields = array_keys($info->getFieldSelection());
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
						$fields = array_keys($info->getFieldSelection());
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
						$fields = $info->getFieldSelection();

						// forum is special field, we can't fetch it directly
						if (isset($fields['forum']))
						{
							$fetch_forum = true;
							unset($fields['forum']);
						}

						// we need forum_id if user requested forum data
						if ($fetch_forum && empty($fields['forum_id']))
						{
							$fields['forum_id'] = true;
						}

						$sql = 'SELECT ' . implode(',', array_keys($fields)) . '
							FROM ' . TOPICS_TABLE . '
							WHERE topic_id = ' . (int) $args['topic_id'];
						$result = $context->db->sql_query($sql);
						$row = $context->db->sql_fetchrow($result);
						$context->db->sql_freeresult($result);

						return $row;
					},
				],
				'topics'	=> [
					'type'	=> types::listOf(types::topic()),
					'args'	=> [
						'topic_ids'	=> types::listOf(types::id()),
					],
					'resolve'	=> function($_, $args, $context, ResolveInfo $info) {
						$fields = $info->getFieldSelection();

						// forum is special field, we can't fetch it directly
						if (isset($fields['forum']))
						{
							$fetch_forum = true;
							unset($fields['forum']);
						}

						// we need forum_id if user requested forum data
						if ($fetch_forum && empty($fields['forum_id']))
						{
							$fields['forum_id'] = true;
						}

						$sql = 'SELECT ' . implode(',', array_keys($fields)) . '
							FROM ' . TOPICS_TABLE;

						if (!empty($args['topic_ids']))
						{
							$sql .= ' WHERE ' . $context->db->sql_in_set('topic_id', $args['topic_ids']);
						}

						$result = $context->db->sql_query($sql);
						$rows = $context->db->sql_fetchrowset($result);
						$context->db->sql_freeresult($result);

						return $rows;
					},
				],
			],
		];
		parent::__construct($config);
	}
}
