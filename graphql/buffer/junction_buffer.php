<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\buffer;

abstract class junction_buffer
{
	protected $entity_ids = [];
	protected $entity_key;
	protected $fields = [];
	protected $result = [];

	protected $db;
	protected $auth;
	protected $table;
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, $table)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->table = $table;
	}

	public function add($entity_id, $entity_key, $fields = [])
	{
		if ($entity_key !== $this->entity_key || !in_array($entity_id, $this->entity_ids))
		{
			$this->entity_key = $entity_key;

			// add new entity
			$this->entity_ids[] = $entity_id;

			$this->add_fields($fields);

			// reset results
			$this->result = [];
		}
	}

	public function add_fields($fields)
	{
		$this->fields += $fields;
	}

	public function get($entity_id, $entity_key)
	{
		$this->load();
		return !empty($this->result[$entity_id]) ? array_column($this->result[$entity_id], $entity_key) : null;
	}

	protected function load()
	{
		if (empty($this->result))
		{
			$fields = implode(',', $this->fields);
			$sql = 'SELECT ' . $this->get_entity_fields() . ($fields ? ',' . $fields : '') . '
				FROM ' . $this->table;
			
			if (!empty($this->entity_ids))
			{
				$sql .= ' WHERE ' . $this->db->sql_in_set($this->entity_key, $this->entity_ids);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row[$this->entity_key]][] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way; do the same for parent ID
			$this->fields = [];
		}
	}

	protected abstract function get_entity_fields();
}
