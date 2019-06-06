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

class group_buffer extends buffer
{
	protected function get_entity_name()
	{
		return 'group_id';
	}

	protected function get_entity_fields()
	{
		return 'group_id';
	}

	protected function auth_check($row)
	{
		if (!$this->auth->acl_get('u_viewprofile')) {
			return false;
		}
		return $row;
	}
}
