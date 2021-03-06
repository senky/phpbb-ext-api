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

class post_buffer extends buffer
{
	public function get_parent_name()
	{
		return 'topic_id';
	}

	protected function get_entity_name()
	{
		return 'post_id';
	}

	protected function get_entity_fields()
	{
		return 'post_id, topic_id, forum_id, poster_id';
	}

	protected function get_limit_setting()
	{
		return 'posts_per_page';
	}

	protected function auth_check($row)
	{
		if (
			(!empty($row['forum_id']) && !$this->auth->acl_get('f_read', $row['forum_id']))
			||
			(empty($row['forum_id']) && !$this->auth->acl_get('f_read'))
		) {
			return false;
		}
		return $row;
	}
}
