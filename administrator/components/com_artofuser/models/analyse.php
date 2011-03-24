<?php
/**
 * @version		$Id: analyse.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * About Page Model
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserModelAnalyse extends JModel
{
	public function getData()
	{
		$result	= new JObject;
		$db		= $this->getDbo();

		try {
			// Count the number of users.
			$db->setQuery(
				'SELECT COUNT(*) FROM #__users'
			);
			$result->set('count.grandtotal', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// Count the activated users.
			$db->setQuery(
				'SELECT COUNT(*) FROM #__users WHERE activation = '.$db->quote('')
			);
			$result->set('count.activated', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// Count the blocked users.
			$db->setQuery(
				'SELECT COUNT(*) FROM #__users WHERE block > 0'
			);
			$result->set('count.blocked', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// The earliest registration.
			$db->setQuery(
				'SELECT registerDate FROM #__users ORDER BY registerDate ASC',
				0, 1
			);
			$result->set('registered.first', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// The latest registration.
			$db->setQuery(
				'SELECT registerDate FROM #__users ORDER BY registerDate DESC',
				0, 1
			);
			$result->set('registered.last', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// Count the ARO table.
			$db->setQuery(
				'SELECT COUNT(*) FROM #__core_acl_aro'
			);
			$result->set('count.aros', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// Look for missing ARO's
			$db->setQuery(
				'SELECT COUNT(*)' .
				' FROM #__users AS u' .
				' LEFT JOIN #__core_acl_aro AS aro ON aro.value = u.id' .
				' WHERE aro.id IS NULL'
			);
			$result->set('count.missing-aros', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			// Look for missing group maps
			$db->setQuery(
				'SELECT COUNT(*)' .
				' FROM #__users AS u' .
				' LEFT JOIN #__core_acl_aro AS aro ON aro.value = u.id' .
				' LEFT JOIN #__core_acl_groups_aro_map AS map ON map.aro_id = aro.id' .
				' WHERE group_id IS NULL'
			);
			$result->set('count.missing-group-maps', $db->loadResult());
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}


		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return $result;
	}
}