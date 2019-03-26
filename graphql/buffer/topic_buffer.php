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

class topic_buffer
{
	protected $topic_ids = [];
	protected $fields = [];
	protected $result = [];

	protected $db;
	protected $topics_table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $topics_table)
	{
		$this->db = $db;
		$this->topics_table = $topics_table;
	}

	public function add($topic_id, $fields = [])
	{
		if (!in_array($topic_id, $this->topic_ids))
		{
			// add new topic
			$this->topic_ids[] = $topic_id;

			$this->add_fields($fields);

			// reset results
			$this->result = [];
		}
	}

	public function add_fields($fields)
	{
		$this->fields += $fields;
	}

	public function get($topic_id)
	{
		$this->load();
		return $this->result[$topic_id];
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
			$sql = 'SELECT topic_id, ' . implode(',', $this->fields) . '
				FROM ' . $this->topics_table;
			
			if (!empty($this->topic_ids))
			{
				$sql .= ' WHERE ' . $this->db->sql_in_set('topic_id', $this->topic_ids);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row['topic_id']] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way
			$this->fields = [];
		}
	}
}
