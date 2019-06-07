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

class rank_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Rank',
			'needs_buffer'	=> true,
			'fields'		=> function() {
				return [
					'rank_id'		=> types::id(),
					'rank_title'	=> types::string(),
					'rank_min'		=> types::int(),
					'rank_special'	=> types::boolean(),
					'rank_image'	=> types::string(),
				];
			}
		];
		parent::__construct($this->definition);
	}
}
