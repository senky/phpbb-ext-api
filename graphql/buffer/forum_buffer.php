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

class forum_buffer
{
	protected $forum_ids = [];
	protected $fields = [];
	protected $result = [];

	protected $db;
	protected $forums_table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $forums_table)
	{
		$this->db = $db;
		$this->forums_table = $forums_table;
	}

	public function add($forum_id, $fields = [])
	{
		if (!in_array($forum_id, $this->forum_ids))
		{
			// add new forum
			$this->forum_ids[] = $forum_id;

			$this->add_fields($fields);

			// reset results
			$this->result = [];
		}
	}

	public function add_fields($fields)
	{
		$this->fields += $fields;
	}

	public function get($forum_id)
	{
		$this->load();
		return $this->result[$forum_id];
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
			$sql = 'SELECT forum_id, ' . implode(',', $this->fields) . '
				FROM ' . $this->forums_table;
			
			if (!empty($this->forum_ids))
			{
				$sql .= ' WHERE ' . $this->db->sql_in_set('forum_id', $this->forum_ids);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row['forum_id']] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way
			$this->fields = [];
		}
	}
}
