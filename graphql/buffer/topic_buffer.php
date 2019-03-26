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
	protected function get_entity_name()
	{
		return 'topic_id';
	}

	protected function get_entity_fields()
	{
		return 'topic_id, forum_id';
	}
}
