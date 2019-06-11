<?php
/**
 *
 * phpBB API. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, Jakub Senko
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace senky\api\graphql\mutator;

use GraphQL\Type\Definition\ResolveInfo;

class topic extends base
{
	public function create($row, $args, $context, ResolveInfo $info)
	{
		if (!class_exists('parse_message'))
		{
			include($this->root_path . 'includes/message_parser.' . $this->php_ext);
		}
		if (!function_exists('submit_post'))
		{
			include($this->root_path . 'includes/functions_posting.' . $this->php_ext);
		}

		$message_parser = new \parse_message();
		$message_parser->message = $args['message'];
		$message_parser->parse(true, $this->config['allow_post_links'], true, true, false, true, $this->config['allow_post_links']);

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
		$redirect_url = \submit_post('post', $args['subject'], $this->user->data['username'], 0, $poll_ary, $data_ary);

		$args['topic_id'] = $this->extract_topic_id($redirect_url);
		$info->fieldName = 'topic';
		return $context->buffer_resolver->resolve($row, $args, $context, $info);
	}

	protected function extract_topic_id($redirect_url)
	{
		preg_match('/;t=(\d+)/', $redirect_url, $matches);
		return $matches[1];
	}
}
