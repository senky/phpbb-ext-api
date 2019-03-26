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

use senky\api\graphql\query;
use senky\api\graphql\types;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\Debug;
use GraphQL\Server\StandardServer;

class main_controller
{
	protected $request;
	protected $context;
	public function __construct(\phpbb\request\request $request, \senky\api\graphql\context $context)
	{
		$this->request = $request;
		$this->context = $context;
	}

	public function handle()
	{
		$this->request->enable_super_globals();
		$server = new StandardServer([
			'schema'	=> new Schema(['query' => new query()]),
			'context'	=> $this->context,
			'debug'		=> Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE,
		]);
		$server->handleRequest();
		die;
	}
}
