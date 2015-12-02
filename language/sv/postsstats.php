<?php
/**
*
* @package PostsStatistics
* @copyright (c) 2014 ForumHulp.com
* @license Proprietary
* @translated into Swedish by Holger (http://www.maskinisten.net)
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
	'ACP_POSTSSTATS'	=> 'Inläggsstatistik',

	'ACP_POSTSSTATS_EXPLAIN'	=> 'Inläggsstatistiken ger en översikt över inlägg och ämnen som skapats inom en viss tidsperiod. Ett diagram visas för varje sida.',

	'PPO'		=> 'Inläggsöversikt',
	'PPU'		=> 'Inlägg per användare',
	'PPT'		=> 'Inlägg per ämne',
	'TPO'		=> 'Ämnesöversikt',
	'TPU'		=> 'Ämnen per användare',
	'TPF'		=> 'Ämnen per forum',
	'TV'		=> 'Ämnesvisningar',
	'GP'		=> 'Gruppinlägg',
	'PTV'		=> 'Inlägg / Ämnen',
	'TT'		=> 'Ämnesuppföljning',
	'POL'		=> 'Poll overview',

	'USERSTATS'	=> 'Användardiagram',
	'LASTVISITS'	=> 'Senaste inlägg',

	'DO'	=> 'Månadsöversikt',
	'DOV'	=> 'Dagsöversikt',
	'MO'	=> 'Årsöversikt',
	'MOV'	=> 'Månadsöversikt',
	'YO'	=> 'Översikt',
	'YOV'	=> 'Alla år i översikt',

	//Config
	'MAX_POSTS_PER_USER'			=> 'Max. inlägg per användare',
	'MAX_POSTS_PER_USER_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_POSTS_PER_TOPIC'			=> 'Max. inlägg per ämnen',
	'MAX_POSTS_PER_TOPIC_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_PER_USER'			=> 'Max. ämnen per användare',
	'MAX_TOPICS_PER_USER_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_PER_FORUM'			=> 'Max. ämnen per forum',
	'MAX_TOPICS_PER_FORUM_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_VIEWS'				=> 'Max. ämnesvisningar',
	'MAX_TOPICS_VIEWS_EXPLAIN'		=> 'Max. poster i vyn',
	'MAX_GROUPS_POSTS'				=> 'Max. gruppinlägg',
	'MAX_GROUPS_POSTS_EXPLAIN'		=> 'Max. poster i vyn',
	'MAX_ONLINE'					=> 'Max. poster i senaste inlägg',
	'MAX_ONLINE_EXPLAIN'			=> 'Max. poster per sida',
	'MAX_TOPIC_TRACKS'				=> 'Max. poster i ämnesuppföljning',
	'MAX_TOPIC_TRACKS_EXPLAIN'		=> 'Max. poster i vyn',

	'START_SCREEN'				=> 'Startbildskärm',
	'START_SCREEN_EXPLAIN'		=> 'Välj din startbildskärm för forumstatistiken och om du vill visa arkiv eller online.',
));
