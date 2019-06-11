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

use Symfony\Component\HttpFoundation\Response;

class schema
{
	protected $query;
	protected $mutation;
	public function __construct(\senky\api\graphql\query $query, \senky\api\graphql\mutation $mutation)
	{
		$this->query = $query;
		$this->mutation = $mutation;
	}

	public function handle()
	{
		$schema = \GraphQL\Utils\SchemaPrinter::doPrint(new \GraphQL\Type\Schema([
			'query'		=> $this->query,
			'mutation'	=> $this->mutation,
		]), [
			'commentDescriptions'	=> true,
		]);
		return new Response($schema);
	}
}
