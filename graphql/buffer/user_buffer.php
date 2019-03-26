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

class user_buffer
{
	protected $user_ids = [];
	protected $fields = [];
	protected $result = [];

	protected $db;
	protected $users_table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $users_table)
	{
		$this->db = $db;
		$this->users_table = $users_table;
	}

	public function add($user_id, $fields = [])
	{
		if (!in_array($user_id, $this->user_ids))
		{
			// add new user
			$this->user_ids[] = $user_id;

			$this->add_fields($fields);

			// reset results
			$this->result = [];
		}
	}

	public function add_fields($fields)
	{
		$this->fields += $fields;
	}

	public function get($user_id)
	{
		$this->load();
		return $this->result[$user_id];
	}

	public function get_all()
	{
		$this->load();
		return $this->result;
	}

	protected function load()
	{
		if (empty($this->result))
		{
			$sql = 'SELECT user_id, ' . implode(',', $this->fields) . '
				FROM ' . $this->users_table;
			
			if (!empty($this->user_ids))
			{
				$sql .= ' WHERE ' . $this->db->sql_in_set('user_id', $this->user_ids);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row['user_id']] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way
			$this->fields = [];
		}
	}
}
