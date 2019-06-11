<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\mutator;

use GraphQL\Type\Definition\ResolveInfo;

class login extends base
{
	public function login($row, $args, $context, ResolveInfo $info)
	{
		return $this->auth->login($args['username'], $args['password']);
	}
}
