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
	'ACP_POSTSSTATS'	=> 'Inl�ggsstatistik',

	'ACP_POSTSSTATS_EXPLAIN'	=> 'Inl�ggsstatistiken ger en �versikt �ver inl�gg och �mnen som skapats inom en viss tidsperiod. Ett diagram visas f�r varje sida.',

	'PPO'		=> 'Inl�ggs�versikt',
	'PPU'		=> 'Inl�gg per anv�ndare',
	'PPT'		=> 'Inl�gg per �mne',
	'TPO'		=> '�mnes�versikt',
	'TPU'		=> '�mnen per anv�ndare',
	'TPF'		=> '�mnen per forum',
	'TV'		=> '�mnesvisningar',
	'GP'		=> 'Gruppinl�gg',
	'PTV'		=> 'Inl�gg / �mnen',
	'TT'		=> '�mnesuppf�ljning',
	'POL'		=> 'Poll overview',

	'USERSTATS'	=> 'Anv�ndardiagram',
	'LASTVISITS'	=> 'Senaste inl�gg',

	'DO'	=> 'M�nads�versikt',
	'DOV'	=> 'Dags�versikt',
	'MO'	=> '�rs�versikt',
	'MOV'	=> 'M�nads�versikt',
	'YO'	=> '�versikt',
	'YOV'	=> 'Alla �r i �versikt',

	//Config
	'MAX_POSTS_PER_USER'			=> 'Max. inl�gg per anv�ndare',
	'MAX_POSTS_PER_USER_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_POSTS_PER_TOPIC'			=> 'Max. inl�gg per �mnen',
	'MAX_POSTS_PER_TOPIC_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_PER_USER'			=> 'Max. �mnen per anv�ndare',
	'MAX_TOPICS_PER_USER_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_PER_FORUM'			=> 'Max. �mnen per forum',
	'MAX_TOPICS_PER_FORUM_EXPLAIN'	=> 'Max. poster i vyn',
	'MAX_TOPICS_VIEWS'				=> 'Max. �mnesvisningar',
	'MAX_TOPICS_VIEWS_EXPLAIN'		=> 'Max. poster i vyn',
	'MAX_GROUPS_POSTS'				=> 'Max. gruppinl�gg',
	'MAX_GROUPS_POSTS_EXPLAIN'		=> 'Max. poster i vyn',
	'MAX_ONLINE'					=> 'Max. poster i senaste inl�gg',
	'MAX_ONLINE_EXPLAIN'			=> 'Max. poster per sida',
	'MAX_TOPIC_TRACKS'				=> 'Max. poster i �mnesuppf�ljning',
	'MAX_TOPIC_TRACKS_EXPLAIN'		=> 'Max. poster i vyn',

	'START_SCREEN'				=> 'Startbildsk�rm',
	'START_SCREEN_EXPLAIN'		=> 'V�lj din startbildsk�rm f�r forumstatistiken och om du vill visa arkiv eller online.',
));
