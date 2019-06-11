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

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\CustomScalarType;

class login_result_type extends ObjectType
{
	public function __construct()
	{
		parent::__construct([
			'name'			=> 'LoginResult',
			'fields'		=> [
				'status'	=> types::int(),
				'error_msg'	=> new CustomScalarType([
					'name'		=> 'ErrorMsg',
					'serialize'	=> function($value) {
						return $value;
					},
				]),
				'user_row'	=> types::user(),
			],
		]);
	}
}
