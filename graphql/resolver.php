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

use GraphQL\Type\Definition\ResolveInfo;

class resolver
{
	public function resolve($row, $args, $context, ResolveInfo $info)
	{
		// types without buffers don't need deferred resolution
		if (empty($info->returnType->config['needs_buffer']))
		{
			return [];
		}

		// translations
		$info->fieldName = $this->translate_field_name($info->fieldName);

		// decide whether we are going to fetch multiple rows or just one
		$is_multiple = substr($info->fieldName, -1) === 's';

		// singular form of a type
		$singular = $is_multiple ? substr($info->fieldName, 0, -1) : $info->fieldName;

		$method = 'resolve_' . ($is_multiple ? 'multiple' : 'single');

		// the second argument is $row in case we are resolving subselection, $args otherwise
		return $this->{$method}($singular, !empty($row) ? $row : $args, $context, $info);
	}

	protected function resolve_single($type, $args, $context, ResolveInfo $info)
	{
		$fields = $info->getFieldSelection();
		$additional_fields = $this->get_additional_fields($fields);
		$fields = $this->clean_fields($fields);
		$fields += $additional_fields;

		$context->{$type . '_buffer'}->add($args[$type . '_id'], $fields);

		return new \GraphQL\Deferred(function() use ($type, $args, $context) {
			return $context->{$type . '_buffer'}->get($args[$type . '_id']);
		});
	}

	protected function resolve_multiple($type, $args, $context, ResolveInfo $info)
	{
		$fields = $info->getFieldSelection();
		$additional_fields = $this->get_additional_fields($fields);
		$fields = $this->clean_fields($fields);
		$fields += $additional_fields;

		$context->{$type . '_buffer'}->add_fields($fields);

		// maybe user specified IDs
		if (!empty($args[$type . '_ids']))
		{
			foreach ($args[$type . '_ids'] as $user_id)
			{
				$context->{$type . '_buffer'}->add($user_id);
			}
		}

		// or maybe user specified parent ID
		$parent_name = $context->{$type . '_buffer'}->get_parent_name();
		if (!empty($args[$parent_name]))
		{
			$context->{$type . '_buffer'}->add_parent($args[$parent_name]);
		}

		return new \GraphQL\Deferred(function() use ($type, $context) {
			return $context->{$type . '_buffer'}->get_all();
		});
	}

	protected static function clean_fields($fields)
	{
		unset($fields['forum'], $fields['topic'], $fields['post_html']);
		return array_keys($fields);
	}

	// TODO: try to rewrite to interfaces: https://webonyx.github.io/graphql-php/type-system/interfaces/
	protected static function get_additional_fields($fields)
	{
		$additional_fields = [];
		if (isset($fields['post_html']))
		{
			$additional_fields = ['post_text', 'bbcode_uid', 'bbcode_bitfield'];
		}
		return $additional_fields;
	}

	protected function translate_field_name($field_name)
	{
		switch ($field_name)
		{
			case 'newest_user':
				return 'user';
			break;
		}
	}
}
