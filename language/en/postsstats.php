<?php
/**
*
* @package Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'ACP_POSTSSTATS'	=> 'Posts Statistics',

	'ACP_POSTSSTATS_EXPLAIN'	=> 'Posts Statistics displays an overview of posts and topics on a timeframe. A graph is shown for every page to have a easy view of your statistics.',

	'PPO'		=> 'Post overview',
	'PPU'		=> 'Posts per user',
	'PPT'		=> 'Posts per topics',
	'TPO'		=> 'Topis overview',
	'TPU'		=> 'Topics per user',
	'TPF'		=> 'Topics per forum',
	'TV'		=> 'Topics views',
	'GP'		=> 'Groups posts',
	'PTV'		=> 'Post / Topics',
	
	'USERSTATS'	=> 'User graph',
	'LASTVISITS'	=> 'Last posts',

	'DO'	=> 'Monthly overview',
	'DOV'	=> 'Daily overview',
	'MO'	=> 'Yearly Overview',
	'MOV'	=> 'Monthly overview',
	'YO'	=> 'Overview',
	'YOV'	=> 'All Years overview',

	//Config
	'MAX_COUNTRIES'		=> 'Countries',
	'MAX_REFERER'		=> 'Referrals',
	'MAX_SE'			=> 'Search engines',
	'MAX_SE_TERMS'		=> 'Search terms',
	'MAX_BROWSERS'		=> 'Browsers',
	'MAX_CRAWL'			=> 'Web Crawlers',
	'MAX_OS'			=> 'Computer Systems',
	'MAX_MODULES'		=> 'Modules',
	'MAX_USERS'			=> 'Users',
	'MAX_AVERAGES'		=> 'Averages',
	'MAX_SCREENS'		=> 'Screen Resolutions',
	'MAX_ONLINE'		=> 'Online',
	'DELL'				=> 'Delete',
	'SEARCHENG_EXPLAIN'	=> 'Change, edit, add or delete searchengines.',

	'MAX_COUNTRIES_EXPLAIN'		=> 'See explanation at modules.',
	'MAX_REFERER_EXPLAIN'		=> 'See explanation at modules',
	'MAX_SE_EXPLAIN'			=> 'See explanation at modules',
	'MAX_SE_TERMS_EXPLAIN'		=> 'See explanation at modules',
	'MAX_BROWSERS_EXPLAIN'		=> 'See explanation at modules',
	'MAX_CRAWL_EXPLAIN'			=> 'See explanation at modules',
	'MAX_OS_EXPLAIN'			=> 'See explanation at modules',
	'MAX_MODULES_EXPLAIN'		=> 'Maximum records in view display before pagination is in order, in table maximum records for pruning',
	'MAX_USERS_EXPLAIN'			=> 'See explanation at modules',
	'MAX_AVERAGES_EXPLAIN'		=> 'See explanation at modules',
	'MAX_SCREENS_EXPLAIN'		=> 'See explanation at modules',
	'MAX_ONLINE_EXPLAIN'		=> 'See explanation at modules',

	'START_SCREEN'				=> 'Start screen',
	'START_SCREEN_EXPLAIN'		=> 'Choose your startscreen for Board Statistics and if you want to display archive or online.',

));
