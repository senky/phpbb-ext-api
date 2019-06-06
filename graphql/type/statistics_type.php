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

use senky\api\graphql\type\types;
use GraphQL\Type\Definition\ResolveInfo;

class statistics_type extends type
{
	public function __construct()
	{
		$this->definition = [
			'name'			=> 'Statistics',
			'fields'		=> function() {
				return [
					'total_posts'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return (int) $context->config['num_posts'];
						},
					],
					'total_topics'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return (int) $context->config['num_topics'];
						},
					],
					'total_forums'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							$sql = 'SELECT COUNT(forum_id) as total_forums
								FROM ' . $context->forums_table;
							$result = $context->db->sql_query($sql);
							$total_forums = (int) $context->db->sql_fetchfield('total_forums', $result);
							$context->db->sql_freeresult($result);
							return $total_forums;
						},
					],
					'total_users'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return (int) $context->config['num_users'];
						},
					],
					'newest_user'	=> [
						'needs_translation'	=> true,
						'type'				=> types::user(),
						'resolve'			=> function($row, $args, $context, ResolveInfo $info) {
							$args['user_id'] = $context->config['newest_user_id'];
							return $context->resolver->resolve($row, $args, $context, $info);
						},
					],
					'online_registered'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return $this->obtain_users_online('visible_online', $context);
						},
					],
					'online_hidden'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return $this->obtain_users_online('hidden_online', $context);
						},
					],
					'online_guests'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return $this->obtain_users_online('guests_online', $context);
						},
					],
					'online_record'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return (int) $context->config['record_online_users'];
						},
					],
					'online_record_time'	=> [
						'type'		=> types::int(),
						'resolve'	=> function($row, $args, $context) {
							return (int) $context->config['record_online_date'];
						},
					],
				];
			}
		];
		parent::__construct($this->definition);
	}

	protected function obtain_users_online($type, $context) {
		static $online_users = null;

		if (!$context->config['load_online'] || !$context->config['load_online_time'])
		{
			return -1;
		}

		if ($type === 'guests_online' && !$context->config['load_online_guests'])
		{
			return -1;
		}

		if (!$online_users)
		{
			$online_users = obtain_users_online();
		}
		return $online_users[$type] ?? -1;
	}
}
