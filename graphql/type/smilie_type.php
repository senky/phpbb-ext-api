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

class smilie_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Smiley',
			'needs_buffer'	=> true,
			'fields'		=> function() {
				return [
					'smiley_id'				=> types::id(),
					'code'					=> types::string(),
					'emotion'				=> types::string(),
					'smiley_url'			=> types::string(),
					'smiley_width'			=> types::int(),
					'smiley_height'			=> types::int(),
					'smiley_order'			=> types::int(),
					'display_on_posting'	=> types::boolean(),
				];
			}
		];
		parent::__construct($this->definition);
	}
}
