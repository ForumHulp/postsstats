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
	'ACP_POSTSSTATS'	=> 'Estadísticas de mensajes',

	'ACP_POSTSSTATS_EXPLAIN'	=> 'Estadísticas de mensajes muestra una visión general de mensajes y temas en un marco de tiempo. Se muestra un gráfico para cada página para tener una visión sencilla de sus estadísticas.',

	'PPO'		=> 'Visión general de mensajes',
	'PPU'		=> 'Mensajes por usuario',
	'PPT'		=> 'Mensajes por temas',
	'TPO'		=> 'Visión general de temas',
	'TPU'		=> 'Temas por usuario',
	'TPF'		=> 'Temas por foro',
	'TV'		=> 'Vistas de temas',
	'GP'		=> 'Mensajes de grupos',
	'PTV'		=> 'Mensajes / Temas',
	'TT'		=> 'Pistas del Tema',
	'POL'		=> 'Poll overview',

	'USERSTATS'	=> 'Gráfico de usuario',
	'LASTVISITS'	=> 'Últimos mensajes',

	'DO'	=> 'Visión general mensual',
	'DOV'	=> 'Visión general diaria',
	'MO'	=> 'Visión general anual',
	'MOV'	=> 'Visión general mensual',
	'YO'	=> 'Visión general',
	'YOV'	=> 'Visión general de todos los años',

	//Config
	'MAX_POSTS_PER_USER'			=> 'Max. mensajes por usuario',
	'MAX_POSTS_PER_USER_EXPLAIN'	=> 'Max. registros en vistas',
	'MAX_POSTS_PER_TOPIC'			=> 'Max. mensajes por temas',
	'MAX_POSTS_PER_TOPIC_EXPLAIN'	=> 'Max. registros en vistas',
	'MAX_TOPICS_PER_USER'			=> 'Max. temas por usuario',
	'MAX_TOPICS_PER_USER_EXPLAIN'	=> 'Max. registros en vistas',
	'MAX_TOPICS_PER_FORUM'			=> 'Max. temas por foro',
	'MAX_TOPICS_PER_FORUM_EXPLAIN'	=> 'Max. registros en vistas',
	'MAX_TOPICS_VIEWS'				=> 'Max. vistas de temas',
	'MAX_TOPICS_VIEWS_EXPLAIN'		=> 'Max. registros en vistas',
	'MAX_GROUPS_POSTS'				=> 'Max. mensajes de grupos',
	'MAX_GROUPS_POSTS_EXPLAIN'		=> 'Max. registros en vistas',
	'MAX_ONLINE'					=> 'Max. registros en últimos mensajes',
	'MAX_ONLINE_EXPLAIN'			=> 'Max. registros por página',

	'START_SCREEN'				=> 'Pantalla de inicio',
	'START_SCREEN_EXPLAIN'		=> 'Elija su pantalla de inicio de Estadísticas del foro y si desea mostrar los archivos o en línea.',
));
