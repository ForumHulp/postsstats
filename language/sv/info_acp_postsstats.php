<?php
/**
*
* @package Posts Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'ACP_POSTSTATISTICS'			=> 'Inläggsstatistik',
));
