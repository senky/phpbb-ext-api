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

use senky\api\graphql\type\forum_type;
use senky\api\graphql\type\post_type;
use senky\api\graphql\type\topic_type;
use senky\api\graphql\type\user_type;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class types
{
	private static $forum;
	private static $post;
	private static $topic;
	private static $user;

	public static function forum()
	{
		return self::$forum ?: (self::$forum = new forum_type());
	}

	public static function post()
	{
		return self::$post ?: (self::$post = new post_type());
	}

	public static function topic()
	{
		return self::$topic ?: (self::$topic = new topic_type());
	}

	public static function user()
	{
		return self::$user ?: (self::$user = new user_type());
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
