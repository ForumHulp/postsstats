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
	'FH_HELPER_NOTICE'		=> '¡La aplicación Forumhulp helper no existe!<br />Descargar <a href="https://github.com/ForumHulp/helper" target="_blank">forumhulp/helper</a> y copie la carpeta helper dentro de la carpeta de extensión forumhulp.',
	'POSTSSTAT_NOTICE'		=> '<div class="phpinfo"><p class="entry">La extensión está en %1$s » %2$s » %3$s.<br>Los ajustes de configuración están en la aplicación.</p></div>',
));

// Description of extension
$lang = array_merge($lang, array(
	'DESCRIPTION_PAGE'		=> 'Descripción',
	'DESCRIPTION_NOTICE'	=> 'Nota de la extensión',
	'ext_details' => array(
		'details' => array(
			'DESCRIPTION_1'		=> 'Vista general gráfica de los mensajes',
			'DESCRIPTION_2'		=> 'Sin carga extra',
			'DESCRIPTION_3'		=> 'Visión general, diaria, mensual y anual',
			'DESCRIPTION_4'		=> 'Configurable',
		),
		'note' => array(
			'NOTICE_1'			=> 'Gráficos de gran calidad',
			'NOTICE_2'			=> 'Imprimible',
			'NOTICE_3'			=> 'Preparado para phpBB 3.2'
		)
	)
));
