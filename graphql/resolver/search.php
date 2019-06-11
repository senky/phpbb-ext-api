<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\resolver;

use GraphQL\Type\Definition\ResolveInfo;

class search
{
	protected $config;
	protected $auth;
	protected $db;
	protected $user;
	protected $dispatcher;
	protected $content_visibility;
	protected $root_path;
	protected $php_ext;
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
		\phpbb\event\dispatcher_interface $dispatcher,
		\phpbb\content_visibility $content_visibility,
		$root_path,
		$php_ext
	)
	{
		$this->config = $config;
		$this->auth = $auth;
		$this->db = $db;
		$this->user = $user;
		$this->dispatcher = $dispatcher;
		$this->content_visibility = $content_visibility;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	public function resolve($row, $args, $context, ResolveInfo $info)
	{
		$type = $this->extract_type($info->fieldName);
		$type_plural = $type . 's';

		$search_type = $this->config['search_type'];
		$error = false;
		$search = new $search_type($error, $this->root_path, $this->php_ext, $this->auth, $this->config, $this->db, $this->user, $this->dispatcher);

		$search->split_keywords($args['keywords'], 'all');
		if ($search->get_search_query())
		{
			$id_ary = [];
			$start = 0;
			$search->keyword_search($type_plural, 'all', 'all', ['t' => 'p.post_time'], 't', 'd', 0, [], $this->content_visibility->get_global_visibility_sql($type, [], $type === 'post' ? 'p.' : 't.'), 0, [], '', $id_ary, $start, $this->config[$type_plural . '_per_page']);

			// we always resolve to only one type
			$info->fieldName = $type_plural;
			$row[$type . '_ids'] = $id_ary;
			return $context->buffer_resolver->resolve($row, $args, $context, $info);
		}
		return [];
	}

	protected function extract_type($field_name)
	{
		switch ($field_name)
		{
			case 'search_posts':
				return 'post';
			break;
			case 'search_topics':
				return 'topic';
			break;
		}
		throw new \Exception('Search type not found.');
	}
}
