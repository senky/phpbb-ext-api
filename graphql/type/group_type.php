<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\type;

use senky\api\graphql\type\types;

class group_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Group',
			'needs_buffer'	=> true,
			'fields'		=> [
				'group_id'				=> types::id(),
				'group_type'			=> types::int(),
				'group_founder_manage'	=> types::boolean(),
				'group_name'			=> types::string(),
				'group_desc'			=> types::string(),
				'group_desc_bitfield'	=> types::string(),
				'group_desc_options'	=> types::int(),
				'group_desc_uid'		=> types::string(),
				'group_display'			=> types::boolean(),
				'group_avatar'			=> types::string(),
				'group_avatar_type'		=> types::int(),
				'group_avatar_width'	=> types::int(),
				'group_avatar_height'	=> types::int(),
				'group_rank'			=> types::int(),
				'group_colour'			=> types::string(),
				'group_sig_chars'		=> types::int(),
				'group_receive_pm'		=> types::boolean(),
				'group_message_limit'	=> types::int(),
				'group_legend'			=> types::boolean(),
			],
		];
		parent::__construct($this->definition);
	}
}
