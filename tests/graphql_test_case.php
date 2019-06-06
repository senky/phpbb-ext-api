<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\tests;

/**
 * @group functional
 */
class graphql_test_case extends \phpbb_functional_test_case
{
	protected $phpbb_config;
	protected $guzzle_client;

	static public function setUpBeforeClass()
	{
		parent::setUpBeforeClass();
	}

	public function setUp()
	{
		parent::setUp();

		$config = [];
		$db = $this->get_db();
		$sql = 'SELECT *
			FROM phpbb_config';
		$result = $db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$config[$row['config_name']] = $row['config_value'];
		}
		$db->sql_freeresult($result);

		$this->phpbb_config = new \phpbb\config\config($config);
		$this->guzzle_client = new \GuzzleHttp\Client;
	}

	protected static function setup_extensions()
	{
		return array('senky/api');
	}

	public function query($query)
	{
		$response = $this->guzzle_client->post(self::$root_url . 'app.php/api/v0', [
			'body'	=> [
				'query'	=> $query,
			]
		]);
		return (string) $response->getBody();
	}

	public function assertResponse($expected, $response)
	{
		$expected_response = [
			'data'	=> $expected,
		];
		$this->assertEquals(json_encode($expected_response), $response);
	}
}
