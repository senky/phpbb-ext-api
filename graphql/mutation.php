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

use senky\api\graphql\type\types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class mutation extends type\type
{
	public function __construct(\phpbb\user $user, \phpbb\config\config $config, $root_path, $php_ext)
	{
		$config = [
			'name'		=> 'Mutation',
			'fields'	=> [
				'createTopic'	=> [
					'type'	=> types::topic(),
					'args'	=> [
						'forum_id'	=> types::nonNull(types::id()),
						'subject'	=> types::nonNull(types::string()),
						'message'	=> types::nonNull(types::string()),
					],
					'resolve'	=> function($row, $args, $context, ResolveInfo $info) use ($user, $config, $root_path, $php_ext) {
						if (!class_exists('parse_message'))
						{
							include($root_path . 'includes/message_parser.' . $php_ext);
						}
						if (!function_exists('submit_post'))
						{
							include($root_path . 'includes/functions_posting.' . $php_ext);
						}

						$message_parser = new \parse_message();
						$message_parser->message = $args['message'];
						$message_parser->parse(true, $config['allow_post_links'], true, true, false, true, $config['allow_post_links']);

						$poll_ary = [];
						$data_ary = [
							'topic_title'		=> $args['subject'],
							'forum_id'			=> $args['forum_id'],
							'icon_id'			=> 0,
							'enable_bbcode'		=> true,
							'enable_smilies'	=> true,
							'enable_urls'		=> true,
							'enable_sig'		=> true,
							'message'			=> $message_parser->message,
							'message_md5'		=> md5($message_parser->message),
							'bbcode_bitfield'	=> $message_parser->bbcode_bitfield,
							'bbcode_uid'		=> $message_parser->bbcode_uid,
							'post_edit_locked'	=> false,
							'enable_indexing'	=> true,
							'notify'			=> false,
							'notify_set'		=> false,
						];
						$redirect_url = \submit_post('post', $args['subject'], $user->data['username'], 0, $poll_ary, $data_ary);

						$info->fieldName = 'topic';
						$args['topic_id'] =  $this->extract_topic_id($redirect_url);
						return $context->resolver->resolve($row, $args, $context, $info);
					},
				],
			],
		];
		parent::__construct($config);
	}

	protected function extract_topic_id($redirect_url)
	{
		preg_match('/;t=(\d+)/', $redirect_url, $matches);
		return $matches[1];
	}
}
