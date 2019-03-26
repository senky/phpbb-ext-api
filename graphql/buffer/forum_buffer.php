<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\buffer;

class forum_buffer
{
	protected static $forum_ids = [];
	protected static $fields = [];
	protected static $result = [];
	public static function add($forum_id, $fields)
	{
		if (!in_array($forum_id, self::$forum_ids))
		{
			// add new forum
			self::$forum_ids[] = $forum_id;

			// union of fields
			self::$fields += $fields;

			// reset results
			self::$result = [];
		}
	}

	public static function get($forum_id)
	{
		global $db;

		if (empty(self::$result))
		{
			$sql = 'SELECT forum_id, ' . implode(',', self::$fields) . '
				FROM ' . FORUMS_TABLE . '
				WHERE ' . $db->sql_in_set('forum_id', self::$forum_ids);
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				self::$result[$row['forum_id']] = $row;
			}
			$db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way
			self::$fields = [];
		}

		return self::$result[$forum_id];
	}
}
