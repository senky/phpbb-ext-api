<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql;

class context
{
	public $db;
	public $config;
	public $resolver;
	public $forum_buffer;
	public $group_buffer;
	public $post_buffer;
	public $topic_buffer;
	public $user_buffer;
	public $user_group_buffer;
	public $forums_table;
	public function __construct(
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\senky\api\graphql\resolver $resolver,
		\senky\api\graphql\buffer\forum_buffer $forum_buffer,
		\senky\api\graphql\buffer\group_buffer $group_buffer,
		\senky\api\graphql\buffer\icon_buffer $icon_buffer,
		\senky\api\graphql\buffer\post_buffer $post_buffer,
		\senky\api\graphql\buffer\topic_buffer $topic_buffer,
		\senky\api\graphql\buffer\user_buffer $user_buffer,
		\senky\api\graphql\buffer\user_group_buffer $user_group_buffer,
		$forums_table
	)
	{
		$this->db = $db;
		$this->config = $config;
		$this->resolver = $resolver;
		$this->forum_buffer = $forum_buffer;
		$this->icon_buffer = $icon_buffer;
		$this->group_buffer = $group_buffer;
		$this->post_buffer = $post_buffer;
		$this->topic_buffer = $topic_buffer;
		$this->user_buffer = $user_buffer;
		$this->user_group_buffer = $user_group_buffer;
		$this->forums_table = $forums_table;
	}
}
