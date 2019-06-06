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
	protected $auth;
	protected $config;
	protected $table;
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\config\config $config, $table)
	{
		$this->db = $db;
		$this->auth = $auth;
		$this->config = $config;
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
		return $this->result[$entity_id] ?? null;
	}

	public function get_all($start, $ids = null)
	{
		$this->load($start);

		if (empty($ids))
		{
			return $this->result;
		}

		return array_intersect_key($this->result, array_flip($ids));
	}

	public function get_parent_name()
	{
		return '';
	}

	protected function load($start = 0)
	{
		if (empty($this->result))
		{
			$where = [];
			$fields = implode(',', $this->fields);
			$sql = 'SELECT ' . $this->get_entity_fields() . ($fields ? ',' . $fields : '') . '
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

			$result = $this->db->sql_query_limit($sql, $this->config[$this->get_limit_setting()], $start, );
			while ($row = $this->db->sql_fetchrow($result))
			{
				$row = $this->auth_check($row);
				if ($row)
				{
					$this->result[$row[$this->get_entity_name()]] = $row;
				}
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way; do the same for parent ID
			$this->fields = [];
			$this->parent_id = 0;
		}
	}

	protected function auth_check($row)
	{
		return $row;
	}

	protected abstract function get_entity_name();
	protected abstract function get_entity_fields();
	protected abstract function get_limit_setting();
}
