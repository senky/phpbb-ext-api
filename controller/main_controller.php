<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\controller;

use GraphQL\Type\Schema;
use GraphQL\Error\Debug;
use GraphQL\Server\StandardServer;

class main_controller
{
	protected $request;
	protected $db;
	protected $config;
	protected $user;
	protected $context;
	protected $query;
	protected $mutation;
	public function __construct(
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\user $user,
		\senky\api\graphql\context $context,
		\senky\api\graphql\query $query,
		\senky\api\graphql\mutation $mutation
	)
	{
		$this->request = $request;
		$this->db = $db;
		$this->config = $config;
		$this->user = $user;
		$this->context = $context;
		$this->query = $query;
		$this->mutation = $mutation;
	}

	public function handle()
	{
		$this->request->enable_super_globals();
		$server = new StandardServer([
			'schema'	=> new Schema([
				'query'		=> $this->query,
				'mutation'	=> $this->mutation,
			]),
			'context'	=> $this->context,
			'debug'		=> Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE,
		]);
		$result = $server->executeRequest();

		if (defined('PHPBB_DISPLAY_LOAD_TIME'))
		{
			if (isset($GLOBALS['starttime']))
			{
				$totaltime = microtime(true) - $GLOBALS['starttime'];
				$result->data['debug']['time'] = [
					'sql'	=> $this->db->get_sql_time(),
					'php'	=> $totaltime - $this->db->get_sql_time(),
					'total'	=> $totaltime,
				];
			}

			$result->data['debug']['queries'] = [
				'cached'	=> $this->db->sql_num_queries(true),
				'total'		=> $this->db->sql_num_queries(),
			];

			$memory_usage = memory_get_peak_usage();
			if ($memory_usage)
			{
				$result->data['debug']['peak_memory_usage'] = $memory_usage;
			}
		}

		if (defined('DEBUG'))
		{
			$result->data['debug']['gzip'] = $this->config['gzip_compress'] && @extension_loaded('zlib');

			if ($this->user->load)
			{
				$result->data['debug']['load'] = $this->user->load;
			}
		}

		$server->getHelper()->sendResponse($result, true);
	}
}
