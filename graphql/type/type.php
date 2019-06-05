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

class type extends ObjectType
{
	protected $definition;

	/**
	 * Some additional fields need other fields to operate.
	 * This method returns those fields.
	 *
	 * @param [string] $fields Requested fields
	 * @return [string] Additional fields
	 */
	public function get_required_fields($fields)
	{
		$additional_fields = [];
		foreach ($fields as $field)
		{
			if (is_array($this->definition['fields'][$field]) && !empty($this->definition['fields'][$field]['requires_fields']))
			{
				$additional_fields += $this->definition['fields'][$field]['requires_fields'];
			}
		}
		return $additional_fields;
	}

	/**
	 * Translate additional fields type when name doesn't match.
	 * This happens sometimes for clarity. E.g. 'poster' is in fact of 'user' type.
	 *
	 * @param string $field Field to be translated
	 * @return string Translated field
	 */
	public function translate_field_name($field)
	{
		if (empty($this->definition['fields'][$field]['type']))
		{
			return $field;
		}

		if ($this->definition['fields'][$field]['type'] instanceof type)
		{
			$class_full = get_class($this->definition['fields'][$field]['type']);
			$class_parts = explode('\\', $class_full);
			$class_name = end($class_parts);
			return substr($class_name, 0, -5);
		}

		return $field;
	}

	/**
	 * Cleans fields from additional fields (not present in DB).
	 *
	 * @param [string] $fields Requested fields
	 * @return [string] Cleaned fields
	 */
	public function clean_fields($fields)
	{
		foreach ($this->definition['fields'] as $field_name => $field_type)
		{
			// we need to remove additional fields. Only they are of array type.
			if (is_array($field_type))
			{
				unset($fields[$field_name]);
			}
		}

		return $fields;
	}
}
