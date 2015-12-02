<?php
/**
*
* @package PostsStatistics
* @copyright (c) 2014 ForumHulp.com
* @license Proprietary
*
*/

namespace forumhulp\postsstats\migrations\v31x;

/**
 * Migration stage 1: Initial schema
 */
class m1_initial_schema extends \phpbb\db\migration\migration
{
	/**
	 * Assign migration file dependencies for this migration
	 *
	 * @return array Array of migration files
	 * @static
	 * @access public
	 */
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\gold');
	}

	/**
	 * Add the table schema to the database:
	 *
	 * @return array Array of table schema
	 * @access public
	 */
	public function update_schema()
	{
		return array(
			// We have to create our own tables
			'add_tables'	=> array(
				$this->table_prefix . 'postsstat_config'	=> array(
					'COLUMNS'			=> array(
						'max_posts_per_user'	=> array('UINT:4', 20),
						'max_posts_per_topic'	=> array('UINT:4', 20),
						'max_topics_per_user'	=> array('UINT:4', 20),
						'max_topics_per_forum'	=> array('UINT:4', 20),
						'max_topics_views'		=> array('UINT:4', 20),
						'max_groups_posts'		=> array('UINT:4', 20),
						'max_online'			=> array('UINT:4', 20),
						'start_screen'			=> array('VCHAR:25', 'default')
					),
				),
				$this->table_prefix . 'postsstat_domains'	=> array(
					'COLUMNS'			=> array(
						'id'			=> array('UINT', null, 'auto_increment'),
						'domain'		=> array('VCHAR:20', ''),
						'description'	=> array('VCHAR:50', ''),
					),
					'PRIMARY_KEY'		=> 'id',
					'KEYS'				=> array(
						'domain'		=> array('INDEX', 'domain')
					)
				)
			)
		);
	}

	/**
	 * Drop the table schema from the database
	 *
	 * @return array Array of table schema
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_tables'	=> array(
				$this->table_prefix . 'postsstat_config',
				$this->table_prefix . 'postsstat_domains'
			),
		);
	}
}
