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

abstract class buffer
{
	protected $entity_ids = [];
	protected $fields = [];
	protected $parent_id = 0;
	protected $result = [];

	protected $db;
	protected $table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $table)
	{
		$this->db = $db;
		$this->table = $table;
	}

	public function add($entity_id, $fields = [])
	{
		if (!in_array($entity_id, $this->entity_ids))
		{
			// add new forum
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

	public function add_parent($parent_id)
	{
		$this->parent_id = $parent_id;
	}

	public function get($entity_id)
	{
		$this->load();
		return $this->result[$entity_id];
	}

	public function get_all()
	{
		$this->load();
		return $this->result;
	}

	public function get_parent_name()
	{
		return '';
	}

	protected function load()
	{
		if (empty($this->result))
		{
			$where = [];
			$sql = 'SELECT ' . $this->get_entity_fields() . ', ' . implode(',', $this->fields) . '
				FROM ' . $this->table;
			
			if (!empty($this->entity_ids))
			{
				$where[] = $this->db->sql_in_set($this->get_entity_name(), $this->entity_ids);
			}
			if ($this->parent_id !== 0)
			{
				$where[] = $this->get_parent_name() . ' = ' . (int) $this->parent_id;
			}

			if (!empty($where))
			{
				$sql .= ' WHERE ' . implode(' AND ', $where);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row[$this->get_entity_name()]] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way; do the same for parent ID
			$this->fields = [];
			$this->parent_id = 0;
		}
	}

	protected abstract function get_entity_name();
	protected abstract function get_entity_fields();
}
