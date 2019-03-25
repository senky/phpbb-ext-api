<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql;

use senky\api\graphql\type\forum_type;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class types
{
	private static $forum;

	public static function forum()
	{
		return self::$forum ?: (self::$forum = new forum_type());
	}

	public static function boolean()
	{
		return Type::boolean();
	}

	public static function id()
	{
		return Type::id();
	}

	public static function int()
	{
		return Type::int();
	}

	public static function string()
	{
		return Type::string();
	}

	public static function listOf($type)
	{
		return new ListOfType($type);
	}

	public static function nonNull($type)
	{
		return new NonNull($type);
	}
}
