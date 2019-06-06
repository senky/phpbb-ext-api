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

class user_group_buffer extends junction_buffer
{
	protected function get_entity_fields()
	{
		return 'group_id, user_id';
	}
}
