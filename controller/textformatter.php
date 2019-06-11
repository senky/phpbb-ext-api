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

class textformatter
{
	protected $cache;
	protected $textformatter_cache;
	public function __construct(\phpbb\cache\driver\driver_interface $cache, \phpbb\textformatter\cache_interface $textformatter_cache)
	{
		$this->cache = $cache;
		$this->textformatter_cache = $textformatter_cache;
	}

	public function handle()
	{
		$output = $this->cache->get('_text_formatter_parser_js');
		if (empty($output))
		{
			$this->textformatter_cache->invalidate();
			$output = $this->cache->get('_text_formatter_parser_js');
		}

		$response = new Response($output);
		$response->headers->set('Content-Type', 'text/javascript');
		return $response;
	}
}
