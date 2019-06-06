<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\tests\graphql;

/**
 * @group functional
 */
class statistics_test extends \senky\api\tests\graphql_test_case
{
	protected static function setup_extensions()
	{
		return array('senky/api');
	}

	public function test_simple_statistics()
	{
		$data = [
			'total_posts'	=> (int) $this->phpbb_config['num_posts'],
			'total_topics'	=> (int) $this->phpbb_config['num_topics'],
			'total_users'	=> (int) $this->phpbb_config['num_users'],
		];

		foreach ($data as $key => $value)
		{
			$expected = [
				'statistics'	=> [
					$key	=> $value,
				]
			];
			$this->assertResponse($expected, $this->query('{
				statistics {
					' . $key . '
				}
			}'));
		}
	}

	public function test_newest_user()
	{
		$expected = [
			'statistics'	=> [
				'newest_user' 	=> [
					'user_id'		=> $this->phpbb_config['newest_user_id'],
					'username'		=> $this->phpbb_config['newest_username'],
					'user_colour'	=> $this->phpbb_config['newest_user_colour'],
				]
			]
		];
		$this->assertResponse($expected, $this->query('{
			statistics {
				newest_user {
					user_id,
					username,
					user_colour
				}
			}
		}'));
	}
}
