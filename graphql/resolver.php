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
		$fields = $this->clean_fields($info->getFieldSelection());
		$context->{$type . '_buffer'}->add($args[$type . '_id'], $fields);

		return new \GraphQL\Deferred(function() use ($type, $args, $context) {
			return $context->{$type . '_buffer'}->get($args[$type . '_id']);
		});
	}

	protected function resolve_multiple($type, $args, $context, ResolveInfo $info)
	{
		$fields = $this->clean_fields($info->getFieldSelection());
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
		unset($fields['forum'], $fields['topic']);
		return array_keys($fields);
	}
}
