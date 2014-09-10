<?php
/**
*
* @package Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\postsstats\acp;

class postsstats_info
{
	function module()
	{
		return array(
			'filename'	=> 'forumhulp\postsstats\acp\postsstats_info',
			'title'		=> 'ACP_POSTSTATISTICS',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'stat'	=> array(
					'title'	=> 'ACP_POSTSTATISTICS',
					'auth'	=> 'ext_forumhulp/postsstats && acl_a_viewlogs',
					'cat'	=> array('ACP_QUICK_ACCESS')
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
