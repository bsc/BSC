<?php
/**
 * @version		$Id: artofuser.php 533 2011-01-15 01:52:59Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	plg_user_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;


jimport('joomla.event.plugin');

/**
 * @package		NewLifeInIT
 * @subpackage	plg_user_artofuser
 * @since		1.0
 */
class plgUserArtofUser extends JPlugin
{
	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param 	array 	holds the user data
	 * @param 	array    extra options
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function onLoginUser($user, $options)
	{
		// Initialize variables
		$instance	= new JUser();
		$userId		= (int) JUserHelper::getUserId($user['username']);
		$instance->load($userId);

		// If the user is blocked, redirect with an error
		if ($instance->get('block') == 1) {

			$db = JFactory::getDbo();
			$db->setQuery(
				'SELECT b.catid, c.title, c.description' .
				' FROM #__artofuser_blocked AS b' .
				' LEFT JOIN #__categories AS c ON c.id = b.catid' .
				' WHERE b.user_id = '.$userId
			);

			$data = $db->loadObject();

			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->getErrorMsg());
			}

			if ($data->catid) {
//				$lang = JFactory::getLanguage();
//				$lang->load('plg_user_artofuser', JPATH_ADMINISTRATOR);

				return JError::raiseWarning(403, $data->description);
			}
		}

		return true;
	}
}