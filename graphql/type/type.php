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
	protected $fields;

	/**
	 * Some additional fields need other fields to operate.
	 * This method returns those fields.
	 *
	 * @param [string] $fields Requested fields
	 * @return [string] Additional fields
	 */
	public function get_required_fields($requested_fields)
	{
		$fields = $this->get_fields();
		$additional_fields = [];
		foreach ($requested_fields as $field)
		{
			if (is_array($fields[$field]) && !empty($fields[$field]['requires_fields']))
			{
				$additional_fields += $fields[$field]['requires_fields'];
			}
		}
		return $additional_fields;
	}

	/**
	 * Cleans fields from additional fields (not present in DB).
	 *
	 * @param [string] $fields Requested fields
	 * @return [string] Cleaned fields
	 */
	public function clean_fields($fields)
	{
		$fields = $this->get_fields();
		foreach ($fields as $field_name => $field_type)
		{
			// we need to remove additional fields. Only they are of array type.
			if (is_array($field_type))
			{
				unset($fields[$field_name]);
			}
		}

		return $fields;
	}

	protected function get_fields()
	{
		if (!empty($this->fields))
		{
			return $this->fields;
		}
		$this->fields = $this->definition['fields']();
		return $this->fields;
	}
}
