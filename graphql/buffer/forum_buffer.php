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

class forum_buffer extends buffer
{
	protected $additional_types = [];
	public function additional_request($types)
	{
		$this->additional_types += $types;
		$this->additional_types = array_unique($this->additional_types);
	}

	protected function get_entity_name()
	{
		return 'forum_id';
	}

	protected function get_entity_fields()
	{
		return 'forum_id';
	}

	protected function get_limit_setting()
	{
		return 'topics_per_page';
	}

	protected function auth_check($row)
	{
		if (
			(!empty($row['forum_id']) && !$this->auth->acl_get('f_list', $row['forum_id']))
			||
			(empty($row['forum_id']) && !$this->auth->acl_get('f_list'))
		) {
			return false;
		}
		return $row;
	}

	protected function get_sql_additions($sql_ary)
	{
		foreach ($this->additional_types as $type)
		{
			switch ($type)
			{
				case 'unread_posts':
					if ($this->config['load_db_lastread'] && $this->user->data['is_registered'])
					{
						$sql_ary['LEFT_JOIN'][] = [
							'FROM' => [FORUMS_TRACK_TABLE => 'ft'],
							'ON' => 'ft.user_id = ' . $this->user->data['user_id'] . ' AND ft.forum_id = f.forum_id'
						];
						$sql_ary['SELECT'] .= ', ft.mark_time';
					}
					else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
					{
						$tracking_topics = $this->request->variable($this->config['cookie_name'] . '_track', '', true, \phpbb\request\request_interface::COOKIE);
						$tracking_topics = ($tracking_topics) ? tracking_unserialize($tracking_topics) : [];
				
						if (!$this->user->data['is_registered'])
						{
							$this->user->data['user_lastmark'] = (isset($tracking_topics['l'])) ? (int) (base_convert($tracking_topics['l'], 36, 10) + $this->config['board_startdate']) : 0;
						}
					}
				break;
			}
		}

		return $sql_ary;
	}
}
