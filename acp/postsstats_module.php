<?php
/**
*
* @package Posts Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
				$user->add_lang(array('install', 'acp/extensions', 'migrator'));
				$ext_name = 'forumhulp/postsstats';
				$md_manager = new \phpbb\extension\metadata_manager($ext_name, $config, $phpbb_extension_manager, $template, $user, $phpbb_root_path);
				try
				{
					$this->metadata = $md_manager->get_metadata('all');
				}
				catch (\phpbb\extension\exception $e)
				{
					trigger_error($e, E_USER_WARNING);
				}

				$md_manager->output_template_data();

				try
				{
					$updates_available = $this->version_check($md_manager, $request->variable('versioncheck_force', false));

					$template->assign_vars(array(
						'S_UP_TO_DATE'		=> empty($updates_available),
						'S_VERSIONCHECK'	=> true,
						'UP_TO_DATE_MSG'	=> $user->lang(empty($updates_available) ? 'UP_TO_DATE' : 'NOT_UP_TO_DATE', $md_manager->get_metadata('display-name')),
					));

					foreach ($updates_available as $branch => $version_data)
					{
						$template->assign_block_vars('updates_available', $version_data);
					}
				}
				catch (\RuntimeException $e)
				{
					$template->assign_vars(array(
						'S_VERSIONCHECK_STATUS'			=> $e->getCode(),
						'VERSIONCHECK_FAIL_REASON'		=> ($e->getMessage() !== $user->lang('VERSIONCHECK_FAIL')) ? $e->getMessage() : '',
					));
				}

				$template->assign_vars(array(
					'U_BACK'	=> $this->u_action,
				));

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

	/**
	* Check the version and return the available updates.
	*
	* @param \phpbb\extension\metadata_manager $md_manager The metadata manager for the version to check.
	* @param bool $force_update Ignores cached data. Defaults to false.
	* @param bool $force_cache Force the use of the cache. Override $force_update.
	* @return string
	* @throws RuntimeException
	*/
	protected function version_check(\phpbb\extension\metadata_manager $md_manager, $force_update = false, $force_cache = false)
	{
		global $cache, $config, $user;
		$meta = $md_manager->get_metadata('all');

		if (!isset($meta['extra']['version-check']))
		{
			throw new \RuntimeException($this->user->lang('NO_VERSIONCHECK'), 1);
		}

		$version_check = $meta['extra']['version-check'];

		$version_helper = new \phpbb\version_helper($cache, $config, new \phpbb\file_downloader(), $user);
		$version_helper->set_current_version($meta['version']);
		$version_helper->set_file_location($version_check['host'], $version_check['directory'], $version_check['filename']);
		$version_helper->force_stability($config['extension_force_unstable'] ? 'unstable' : null);

		return $updates = $version_helper->get_suggested_updates($force_update, $force_cache);
	}
}
