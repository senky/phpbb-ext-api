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
			if (gettype($fields[$field]) === 'array' && !empty($fields[$field]['requires_fields']))
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
	public function clean_fields($requested_fields)
	{
		$fields = $this->get_fields();
		foreach ($requested_fields as $field_name => $_)
		{
			// we need to remove additional fields. Only they are of array type.
			if (gettype($fields[$field_name]) === 'array')
			{
				unset($requested_fields[$field_name]);
			}
		}

		return $requested_fields;
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
