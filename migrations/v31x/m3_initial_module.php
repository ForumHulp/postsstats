<?php
/**
*
* @package Statistics
* @copyright (c) 2014 ForumHulp.com
* @license Proprietary
*
*/

namespace forumhulp\postsstats\migrations\v31x;

/**
* Migration stage 4: Initial module
*/
class m3_initial_module extends \phpbb\db\migration\migration
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
		return array('\forumhulp\postsstats\migrations\v31x\m1_initial_schema');
	}

	/**
	 * Add or update data in the database
	 *
	 * @return array Array of table data
	 * @access public
	 */
	public function update_data()
	{
		return array(
			array('module.add', array(
				'acp', 'ACP_QUICK_ACCESS', array(
					'module_basename'	=> '\forumhulp\postsstats\acp\postsstats_module',
					'modes'				=> array('stat'),
				),
			)),
		);
	}
}
