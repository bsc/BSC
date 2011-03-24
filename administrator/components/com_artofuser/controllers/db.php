<?php
/**
 * @version		$Id: db.php 536 2011-01-15 02:20:18Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright (C) 2009 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * ArtofUser database controller
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserControllerDb extends JController
{
	/**
	 * Exports the extra database tables used by this extension in MySQL XML format.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function export()
	{
		require_once JPATH_ADMINISTRATOR.'/components/com_artofuser/libraries/joomla/database/database/mysqlxml.php';

		echo '<pre>'.htmlspecialchars(
			JDatabaseMySQLXML::export(
				array(
					'#__artofuser_notes',
					'#__artofuser_blocked',
				)
			)
		).'</pre>';
	}
}