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

class topic_buffer extends buffer
{
	public function get_parent_name()
	{
		return 'forum_id';
	}

	protected function get_entity_name()
	{
		return 'topic_id';
	}

	protected function get_entity_fields()
	{
		return 'topic_id, forum_id';
	}

	protected function get_limit_setting()
	{
		return 'topics_per_page';
	}
	
	protected function auth_check($row)
	{
		if (
			(!empty($row['forum_id']) && !$this->auth->acl_get('f_list_topics', $row['forum_id']))
			||
			(empty($row['forum_id']) && !$this->auth->acl_get('f_list_topics'))
		) {
			return false;
		}
		return $row;
	}
}
