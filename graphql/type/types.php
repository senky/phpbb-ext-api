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

use GraphQL\Type\Definition\Type;

class types
{
	private static $forum;
	private static $group;
	private static $icon;
	private static $post;
	private static $login_result;
	private static $rank;
	private static $smilie;
	private static $statistics;
	private static $topic;
	private static $user;

	public static function __callStatic($name, $args)
	{
		if (method_exists(__CLASS__, $name)) {
			return self::$name;
		}

		$class_name = 'senky\\api\\graphql\\type\\' . $name . '_type';
		return self::$$name ?: (self::$$name = new $class_name());
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

	public static function float()
	{
		return Type::float();
	}

	public static function string()
	{
		return Type::string();
	}

	public static function listOf($type)
	{
		return Type::listOf($type);
	}

	public static function nonNull($type)
	{
		return Type::nonNull($type);
	}
}
