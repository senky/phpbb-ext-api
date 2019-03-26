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

class post_buffer
{
	protected $post_ids = [];
	protected $fields = [];
	protected $result = [];

	protected $db;
	protected $posts_table;
	public function __construct(\phpbb\db\driver\driver_interface $db, $posts_table)
	{
		$this->db = $db;
		$this->posts_table = $posts_table;
	}

	public function add($post_id, $fields = [])
	{
		if (!in_array($post_id, $this->post_ids))
		{
			// add new topic
			$this->post_ids[] = $post_id;

			$this->add_fields($fields);

			// reset results
			$this->result = [];
		}
	}

	public function add_fields($fields)
	{
		$this->fields += $fields;
	}

	public function get($post_id)
	{
		$this->load();
		return $this->result[$post_id];
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
			$sql = 'SELECT post_id, topic_id, forum_id, ' . implode(',', $this->fields) . '
				FROM ' . $this->posts_table;
			
			if (!empty($this->post_ids))
			{
				$sql .= ' WHERE ' . $this->db->sql_in_set('post_id', $this->post_ids);
			}

			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->result[$row['post_id']] = $row;
			}
			$this->db->sql_freeresult($result);

			// reset fields - next query won't fetch unnecessary fields this way
			$this->fields = [];
		}
	}
}
