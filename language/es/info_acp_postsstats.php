<?php
/**
*
* @package PostsStatistics
* @copyright (c) 2014 ForumHulp.com
* @license Proprietary
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_POSTSTATISTICS'	=> 'Estadísticas de mensajes',
	'FH_HELPER_NOTICE'		=> 'Forumhulp helper application does not exist!<br />Download <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> and copy the helper folder to your forumhulp extension folder.',
	'POSTSSTAT_NOTICE'		=> '<div class="phpinfo"><p class="entry">This extension resides in %1$s » %2$s » %3$s.<br>Config settings are within the application.</p></div>',
));

// Description of extension
$lang = array_merge($lang, array(
	'DESCRIPTION_PAGE'		=> 'Description',
	'DESCRIPTION_NOTICE'	=> 'Extension note',
	'ext_details' => array(
		'details' => array(
			'DESCRIPTION_1'		=> 'Graphical overview of posts',
			'DESCRIPTION_2'		=> 'No extra load',
			'DESCRIPTION_3'		=> 'Dailly, Monthly and Yearly overviews',
			'DESCRIPTION_4'		=> 'Configurable',
		),
		'note' => array(
			'NOTICE_1'			=> 'Highchart graphics',
			'NOTICE_2'			=> 'Printable',
			'NOTICE_3'			=> 'phpBB 3.2 ready'
		)
	)
));
