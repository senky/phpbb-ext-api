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
use GraphQL\Type\Definition\ResolveInfo;

class icon_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Icon',
			'needs_buffer'	=> true,
			'fields'		=> function() {
				return [
					'icons_id'				=> types::id(),
					'icons_url'				=> types::string(),
					'icons_width'			=> types::int(),
					'icons_height'			=> types::int(),
					'icons_alt'				=> types::string(),
					'icons_order'			=> types::int(),
					'display_on_posting'	=> types::boolean(),
				];
			}
		];
		parent::__construct($this->definition);
	}
}
