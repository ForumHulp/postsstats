<?php
/**
*
* @package PostsStatistics
* @copyright (c) 2014 ForumHulp.com
* @license Proprietary
*
*/

namespace forumhulp\postsstats\acp;

class postsstats_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $db, $config, $sconfig, $phpbb_root_path, $user, $template, $request, $phpbb_extension_manager, $phpbb_container, $phpbb_path_helper, $tables;
		$user->add_lang_ext('forumhulp/postsstats', 'postsstats');

		include($phpbb_root_path . 'ext/forumhulp/postsstats/vendor/stat_functions.php');
		$tables['config']	= $phpbb_container->getParameter('tables.poststatconfig_table');
		$tables['domain']	= $phpbb_container->getParameter('tables.poststatdomain_table');
		\stat_functions::get_config();

		$action		= $request->variable('action', '');
		$screen		= $request->variable('screen', $sconfig['start_screen']);
		$start		= $request->variable('start', 0);
		$type		= $request->variable('t', 0);
		$month 		= $request->variable('m', date('n', time()));
		$year		= $request->variable('y', date('Y', time()));

		$prev = ($type == 0) ? '&amp;m='.(($month-1 == 0) ? 12 : $month-1).'&amp;y='.(($month-1 == 0) ? $year-1 : $year) : (($type==2) ? '' : '&amp;t='.$type.'&amp;y=' . ($year - 1));
		$next = ($type == 0) ? '&amp;m='.(($month+1 == 13) ? 1 : $month+1).'&amp;y='.(($month+1 == 13) ? $year+1 : $year) : (($type==2) ? '' : '&amp;t='.$type.'&amp;y=' . ($year + 1));

		$this->tpl_name = 'acp_statistics';
		$this->page_title = 'ACP_POSTSSTATS';
		$template->assign_vars(array('EXT_PATH' => $phpbb_path_helper->update_web_root_path($phpbb_extension_manager->get_extension_path('forumhulp/postsstats', true)),
									'U_ACTION'	=> $this->u_action,
									'ACT'		=> $screen,
									'VIEW_TABLE' => $request->variable('table', false)));

		switch ($screen)
		{
			case 'info':
				$user->add_lang_ext('forumhulp/postsstats', 'info_acp_postsstats');
				$phpbb_container->get('forumhulp.helper')->detail('forumhulp/postsstats');
				$this->tpl_name = 'acp_ext_details';
			break;

			case 'nyi':
				\stat_functions::nyi($start, $this->u_action);
			break;

			case 'posts':
				\stat_functions::posts($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'ppu':
				\stat_functions::ppu($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'ppt':
				\stat_functions::ppt($type, $month, $year,  $next, $prev, $this->u_action);
			break;

			case 'topics':
				\stat_functions::topics($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'tpu':
				\stat_functions::tpu($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'tpf':
				\stat_functions::tpf($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'tv':
				\stat_functions::tv($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'gp':
				\stat_functions::gp($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'ptv':
				\stat_functions::ptv($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'poll':
				\stat_functions::poll($type, $month, $year, $next, $prev, $this->u_action);
			break;

			case 'config':
				\stat_functions::config($start, $this->u_action);
			break;

			default:
				\stat_functions::online($start, $this->u_action);
			break;
		}
	}
}
