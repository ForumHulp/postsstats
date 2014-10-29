<?php
/**
*
* @package Posts Statistics
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class stat_functions
{
	/**
	* get_config data
	*/
	public static function get_config()
	{
		global $db, $sconfig, $tables;

		$sql = 'SELECT * FROM ' . $tables['config'];
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		foreach ($row as $key => $value)
		{
			$sconfig[$key] = $value;
		}
	}

	public static function count_array($aray, $row1)
	{
		$found = 0;
		if (is_array($aray))
		{
			foreach ($aray as $key => $value)
			{
				if ($key == $row1)
				{
					$aray[$row1] += 1;
					$found = 1;
					break;
				}
			}
		}
		if (!$found)
		{
			$aray[$row1] = 1;
		}
		return $aray;
	}

	public static function days_in_month($month, $year) 
	{ 
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
	} 

	public static function posts($type = 0, $month, $year, $next, $prev, $uaction = '', $ptv = false)
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT COUNT(post_id) as total, ' .(($type == 1) ? 'MONTH' : (($type == 2) ? 'YEAR' : 'DAY')) . '(FROM_UNIXTIME(post_time)) as posts_per FROM ' . POSTS_TABLE . ' 
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(post_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(post_time)) = ' . $month : '') . '  
				GROUP BY posts_per ORDER BY ' .(($type == 1) ? 'MONTH' : (($type == 2) ? 'YEAR' : 'DAY')) . '(FROM_UNIXTIME(post_time))';
		$result = $db->sql_query($sql);
		$series = $categories = $title = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$data[$row['posts_per']] = $row['total'];	
		}

		for($i = 1; $i <= (($type == 0) ? self::days_in_month($month, $year) : (($type == 2) ? sizeof($data): 12)); $i++)
		{
			$t = ($type == 2) ? key($data) + $i - 1 : $i;
		    
   			$series['name'] = ($i == 1) ? 'Post' : $series['name'];
			$series['data'][] = (isset($data[$t])) ? $data[$t] : 0;
			$categories['data'][] = $t;
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DOV'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MOV'] . ' ' . $year : $user->lang['YOV']));
		$title['descr'][] = $user->lang['PPO'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);

		if ($ptv)
		{
			return $result;	
		}
	
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES' 		=> '[' . implode(',', $categories['data']) . ']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['POSTS'] . '\'',
			'LABELENABLE'	=> 'true',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));

		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function ppu($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT u.username, COUNT(p.post_id) AS total FROM ' . POSTS_TABLE . ' p
				LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.poster_id
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(p.post_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(p.post_time)) = ' . $month : '') . '
				GROUP BY p.poster_id ORDER BY total DESC LIMIT ' . $sconfig['max_posts_per_user'];
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Post' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = $db->sql_escape($row['username']);
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		array_push($ser, $series);
		$title['descr'][] = $user->lang['PPU'] . ': ';
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['POSTS'] . '\'',
			'LABELENABLE'	=> 'false',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));
		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function ppt($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT p.topic_id, p.topic_title, p.topic_replies AS total FROM ' . TOPICS_TABLE . ' p
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(p.topic_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(p.topic_time)) = ' . $month : '') . '  
				ORDER BY total DESC LIMIT ' . $sconfig['max_posts_per_topic'];
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Post' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = $db->sql_escape($row['topic_title']);
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		$title['descr'][] = $user->lang['PPT'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['POSTS'] . ' /\ ' . $user->lang['TOPICS'] . '\'',
			'LABELENABLE'	=> 'false',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));

		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function topics($type = 0, $month, $year, $next, $prev, $uaction = '', $ptv = false)
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT COUNT(topic_id) as total, ' .(($type == 1) ? 'MONTH' : (($type == 2) ? 'YEAR' : 'DAY')) . '(FROM_UNIXTIME(topic_time)) as topics_per FROM ' . TOPICS_TABLE . ' 
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(topic_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(topic_time)) = ' . $month : '') . '  
				GROUP BY topics_per ORDER BY DAY(FROM_UNIXTIME(topic_time))';
		$result = $db->sql_query($sql);
		$series = $categories = $title = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$data[$row['topics_per']] = $row['total'];	
		}

		for($i = 1; $i <= (($type == 0) ? self::days_in_month($month, $year) : (($type == 2) ? sizeof($data): 12)); $i++)
		{
			$t = ($type == 2) ? key($data) + $i - 1 : $i;
   			$series['name'] = ($i == 1) ? 'Topics' : $series['name'];
			$series['data'][] = (isset($data[$t])) ? $data[$t] : 0;
			$categories['data'][] = $t;
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DOV'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MOV'] . ' ' . $year : $user->lang['YOV']));
		$title['descr'][] = $user->lang['TPO'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);

		if ($ptv)
		{
			return $result;
		}
		
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[' . implode(',', $categories['data']) . ']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['TOPICS'] . '\'',
			'LABELENABLE'	=> 'true',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));

		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function tpu($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $sconfig, $user, $request, $template;

		$sql = 'SELECT p.topic_poster, u.username, COUNT(p.topic_id) AS total FROM ' . TOPICS_TABLE . ' p
				LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.topic_poster
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(p.topic_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(p.topic_time)) = ' . $month : '') . '  
				GROUP BY p.topic_poster ORDER BY total DESC LIMIT ' . $sconfig['max_topics_per_user'];
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Topics' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = $db->sql_escape($row['username']);
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		$title['descr'][] = $user->lang['TPU'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['TOPICS'] . '\'',
			'BTNEN'			=> 'true',
			'LABELENABLE'	=> 'false',
			'SUB_DISPLAY'	=> 'stats'
		));
		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function tpf($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT forum_id, forum_name, forum_topics_real AS total FROM ' . FORUMS_TABLE . '
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(forum_last_post_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(forum_last_post_time)) = ' . $month : '') . ' ORDER BY total DESC LIMIT ' . $sconfig['max_topics_per_forum'];
		
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Topics' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = $db->sql_escape($row['forum_name']);
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		$title['descr'][] = $user->lang['TPF'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['TOPICS'] . ' /\ ' . $user->lang['FORUM'] . '\'',
			'LABELENABLE'	=> 'false',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));
		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function tv($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT topic_title, topic_views AS total FROM ' . TOPICS_TABLE . '
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(topic_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(topic_time)) = ' . $month : '') . ' ORDER BY total DESC LIMIT ' . $sconfig['max_topics_views'];
		
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Topics' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = $db->sql_escape($row['topic_title']);
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		$title['descr'][] = $user->lang['TV'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['TOPICS'] . '\'',
			'LABELENABLE'	=> 'false',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));
		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function gp($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$sql = 'SELECT u.group_id, g.group_name, COUNT(p.post_id) AS total FROM ' . POSTS_TABLE . ' P
				LEFT JOIN ' . USERS_TABLE . ' U ON U.user_id = p.poster_id
				LEFT JOIN ' . GROUPS_TABLE . ' g ON g.group_id = u.group_id
				WHERE ' . (($type < 2) ? 'YEAR(FROM_UNIXTIME(p.post_time)) = ' . $year : '1 = 1') . (($type == 0) ? ' AND MONTH(FROM_UNIXTIME(p.post_time)) = ' . $month : '') . ' 
				GROUP BY g.group_id ORDER BY total DESC LIMIT ' . $sconfig['max_groups_posts'];
		
		$result = $db->sql_query($sql);
		$series = $categories = $title = $categories['data'] = $series['data'] = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
   			$series['name'] = (++$i == 1) ? 'Posts' : $series['name'];
			$series['data'][] = (isset($row['total'])) ? $row['total'] : 0;
			$categories['data'][] = isset($row['group_name']) ? $db->sql_escape($user->lang['G_' . $row['group_name']]) : 'Unknown';
		}
		$result = $ser = array();
		$title['title'][] = (($type == 0) ? $user->lang['DO'] . ' ' . date("F",mktime(0,0,0,$month,1,$year)) . ' ' . $year :
							(($type == 1) ? $user->lang['MO'] . ' ' . $year : $user->lang['YO']));
		$title['descr'][] = $user->lang['GP'] . ': ';
		array_push($ser, $series);
		array_push($result, $ser, $categories, $title);
		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> '[' . json_encode($series, JSON_NUMERIC_CHECK) . ']',
			'DATES'			=> '[\'' . (isset($categories['data']) ? implode('\', \'', $categories['data']) : '') . '\']',
			'TITLE'			=> $title['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['POSTS'] . ' /\ ' . $user->lang['GROUP'] . '\'',
			'LABELENABLE'	=> 'false',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));
		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}
	}

	public static function ptv($type = 0, $month, $year, $next, $prev, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $request, $template;

		$ptv = true;
		$result = $series = array();
		$posts = self::posts($type, $month, $year, $next, $prev, $uaction, $ptv);
		$topics = self::topics($type, $month, $year, $next, $prev, $uaction, $ptv);
	
		$posts[2]['descr'][0] = substr($posts[2]['descr'][0], 0, -2) . ' / ' . $user->lang['TOPICS'] . ': ';
		array_push($series, $posts[0][0], $topics[0][0]);
		array_push($result, $series, $posts[1], $posts[2]);

		$template->assign_vars(array(
			'U_ACTION'		=> $uaction,
			'PREV'			=> $prev,
			'NEXT'			=> $next,
			'STATS'			=> json_encode($result[0], JSON_NUMERIC_CHECK),
			'DATES' 		=> '[' . implode(',', $result[1]['data']) . ']',
			'TITLE'			=> $posts[2]['title'][0],
			'HITSTITLE'		=> '\'' . $user->lang['POSTS'] . ' /\ ' . $user->lang['TOPICS'] . '\'',
			'LABELENABLE'	=> 'true',
			'BTNEN'			=> 'true',
			'SUB_DISPLAY'	=> 'stats'
		));

		if ($request->variable('table', false))
		{
			print json_encode($result, JSON_NUMERIC_CHECK);
		}

	}

	public static function online($start = 0, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $tables, $request, $template, $phpbb_container;

		// sort keys, direction en sql
		$sort_key	= $request->variable('sk', 't');
		$sort_dir	= $request->variable('sd', 'd');
		$sort_by_sql = array('t' => 'time', 'u' => 'uname', 'm' => 'module', 'd' => 'domain', 'h' => 'host');
		$sql_sort = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$template->assign_vars(array(
			'U_ACTION'			=> $uaction,
			'S_SORT_KEY'		=> $sort_key,
			'S_SORT_DIR'		=> $sort_dir,
			'VIEW_TABLE' 		=> $request->variable('table', false),
			'SUB_DISPLAY'		=> 'online'
		));

		$sql = 'SELECT COUNT(post_id) AS total_entries FROM ' . POSTS_TABLE . ' WHERE post_time BETWEEN ' . mktime(0,0,0) . ' AND ' . (mktime(0,0,0) + 86400);
		$result = $db->sql_query($sql);
		$total_entries = (int) $db->sql_fetchfield('total_entries');
		$db->sql_freeresult($result);

		$pagination = $phpbb_container->get('pagination');
		$base_url = $uaction . '&amp;screen=online&amp;sk=' . $sort_key . '&amp;sd=' . $sort_dir;
		$pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_entries, $sconfig['max_online'], $start);

		$sql = 'SELECT p.post_time, p.post_subject, p.poster_ip, p.forum_id, p.topic_id, p.post_id, u.username FROM ' . POSTS_TABLE . ' p
				LEFT JOIN ' . USERS_TABLE . ' u ON (u.user_id = p.poster_id) WHERE p.post_time BETWEEN ' . mktime(0,0,0) . ' AND ' . (mktime(0,0,0) + 86400). ' ORDER BY p.post_time DESC';
		$result = $db->sql_query_limit($sql, $sconfig['max_online'], $start);
		$counter = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$counter += 1;
			$data['host'] = @gethostbyaddr(($row['poster_ip']));
			$aray = explode('.', $data['host']);
			$data['domain'] = ($row['poster_ip'] == '127.0.0.1') ? 'lo' : strtolower($aray[sizeof($aray) -1]);
			$data['domain'] = (!file_exists('../ext/forumhulp/postsstats/adm/style/images/flags/' . $data['domain'] . '.png')) ? 'un' : $data['domain'];
			$sql = 'SELECT description FROM ' . $tables['domain'] . ' WHERE domain = "' . $data['domain'] . '"';
			$result1 = $db->sql_query($sql);
			$data['description'] = $db->sql_fetchfield('description');
			
			$template->assign_block_vars('onlinerow', array(
				'COUNTER'   => $start + $counter,
				'TIJD'		=> $user->format_date($row['post_time'], 'H:i'),
				'DATE'		=> $user->format_date($row['post_time'], 'D M d, Y'),
				'FLAG'		=> ($row['username'] != 'Anonymous') ? 'online-user.gif' : 'offline-user.gif',
				'UNAME'		=> $row['username'],
				'MODULE'	=> $row['post_subject'],
				'MODULEURL'	=> '/viewtopic.php?f='. $row['forum_id'] . '&t=' . $row['topic_id'] . '#p ' . $row['post_id'],
				'DFLAG'		=> $data['domain'].'.png',
				'DDESC'		=> $data['description'],
				'HOST'		=> $data['host'],
				'IP'		=> $row['poster_ip']
				)
			);
		}
	}

	public static function roundk($value)
	{
		if ($value > 999 && $value <= 999999)
		{
			$result = floor($value / 1000) . ' K';
		} else if ($value > 999999)
		{
			$result = floor($value / 1000000) . ' M';
		} else
		{
			$result = $value;
		}
		return $result;
	}

	public static function config($start = 0, $uaction = '')
	{
		global $db, $config, $sconfig, $user, $tables, $request, $template, $phpbb_container;

		$sconfig = $request->variable('config', array('' => 0), true);
		if (sizeof($sconfig))
		{
			$sql = 'UPDATE ' . $tables['config'] . ' SET ' . $db->sql_build_array('UPDATE', $sconfig);
			$db->sql_query($sql);
		}

		if ($request->is_set('submit_start_screen'))
		{
			$sql = 'UPDATE ' . $tables['config'] . ' SET start_screen = "' . $request->variable('start_screen', 'default') . '"';
			$db->sql_query($sql);
		}

		$sql = 'SELECT * FROM ' . $tables['config'];
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		for ($i = 0; $i < sizeof($row) - 1; $i++)
		{
			$template->assign_block_vars('options', array(
				'KEY'			=> strtoupper(key($row)),
				'TITLE'			=> $user->lang[strtoupper(key($row))],
				'S_EXPLAIN'		=> (isset($user->lang[strtoupper(key($row)) . '_EXPLAIN'])) ? true : false,
				'TITLE_EXPLAIN'	=> (isset($user->lang[strtoupper(key($row)) . '_EXPLAIN'])) ? $user->lang[strtoupper(key($row)) . '_EXPLAIN'] : '',
				'CONTENT'		=> '<input type="number" name="config[' . key($row) . ']" id="config_' . key($row) . '" size="3" value="' . $row[key($row)] . '" />'
			));
			next($row);
		}

		$module_aray = array('gp', 'lastvisits', 'posts', 'ppt', 'ppu', 'ptv', 'tpf', 'tpo', 'tpu', 'tv', 'userstats');
		$optionssc = '';
		foreach($module_aray as $value)
		{
			$selected = ($value == $row['start_screen']) ? ' selected="selected"' : '';
			$optionssc .= '<option value="' . $value . '"' . $selected . '>' . $user->lang[strtoupper($value)] . '</option>';
		}

		$template->assign_vars(array(
			'OPTIONLISTSC'		=> $optionssc,
			'U_ACTION'			=> $uaction . '&amp;screen=config',
			'SUB_DISPLAY'		=> 'config'
		));
	}

	public static function nyi($start = 0, $uaction = '')
	{
		global $db, $config, $user, $tables, $request, $template, $phpbb_container;

		$template->assign_vars(array(
			'U_ACTION'			=> $uaction,
			'SUB_DISPLAY'		=> 'nyi'
		));
	}
}
