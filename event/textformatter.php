<?php
/**
 *
 * Advertisement management. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\event;

class textformatter implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.text_formatter_s9e_configure_after'		=> 'generate_js',
			'core.text_formatter_s9e_configure_finalize'	=> 'cache_js',
		);
	}

	protected $cache;
	public function __construct(\phpbb\cache\driver\driver_interface $cache)
	{
		$this->cache = $cache;
	}

	public function generate_js($event)
	{
		$event['configurator']->enableJavaScript();
		$event['configurator']->javascript->setMinifier('MatthiasMullieMinify');
		$event['configurator']->javascript->exports = ['parse', 'preview'];
	}

	public function cache_js($event)
	{
		$this->cache->put('_text_formatter_parser_js', $event['objects']['js']);
	}
}
