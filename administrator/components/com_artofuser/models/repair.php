<?php
/**
 * @version		$Id: repair.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Fix Model
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserModelRepair extends JModel
{
	/**
	 * This method fixes mis-matches in the Aro table.
	 */
	public function aros()
	{
		$db		= $this->getDbo();

		try {

			// Look for missing ARO's relative to the users table.
			$db->setQuery(
				'SELECT u.id' .
				' FROM #__users AS u' .
				' LEFT JOIN #__core_acl_aro AS aro ON aro.value = u.id' .
				' WHERE aro.id IS NULL'
			);
			$result = $db->loadResultArray();
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			if (!empty($result)) {
				// These are existing users that are missing an entry in the ARO table,
				// so we need to create an ARO entry and a Group Map entry.

				$aros = $this->addAros($result);
				$this->addGroupAroMaps($aros);
			}

			// Look for missing users relative to the ARO table.
			$db->setQuery(
				'SELECT aro.id' .
				' FROM #__core_acl_aro AS aro' .
				' LEFT JOIN #__users AS u ON u.id = aro.value' .
				' WHERE u.id IS NULL'
			);
			$result = $db->loadResultArray();

			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			if (!empty($result)) {
				// These are ARO's that are missing a corresponding user.
				// No recovery is information is available to we delete these entries
				// as well as and group maps.

				$this->removeAros($result);
				$this->removeGroupAroMaps($result);
			}

		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}

	/**
	 * This method fixes mis-matches in the Group ARO Maps table.
	 */
	public function groupAroMaps()
	{
		$db = $this->getDbo();

		try {
			// Look for the missing maps
			$db->setQuery(
				'SELECT aro.id' .
				' FROM #__core_acl_aro AS aro' .
				' LEFT JOIN #__core_acl_groups_aro_map AS map ON map.aro_id = aro.id' .
				' WHERE group_id IS NULL'
			);
			$aroIds = $db->loadResultArray();
			if ($error = $db->getErrorMsg()) {
				throw new Excpetion($error);
			}

			if (!empty($aroIds)) {
				$this->addGroupAroMaps($aroIds);
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}

	/**
	 * Adds ARO records for a list of user id's.
	 *
	 * @param	array	An array of user id's.
	 * @returns	array	A key array of the ARO's added for each user.
	 * @throws	The database error message.
	 */
	protected function addAros($userIds)
	{
		$results = array();
		if (is_array($userIds)) {
			$db = $this->getDbo();

			foreach ($userIds as $userId) {
				// Get the name of the user.
				$db->setQuery(
					'SELECT name FROM #__users WHERE id = '.(int) $userId
				);
				$userName = $db->loadResult();
				if ($error = $db->getErrorMsg()) {
					throw new Excpetion($error);
				}

				$db->setQuery(
					'INSERT INTO #__core_acl_aro (id, section_value, value, order_value, name, hidden) VALUES' .
					'(null,'.$db->quote('users').','.(int) $userId.',0,'.$db->quote($userName).',0)'
				);
				if (!$db->query()) {
					throw new Excpetion($db->getErrorMsg());
				}

				$results[(int) $userId] = (int) $db->insertid();
			}
		}

		return $results;
	}

	/**
	 * Adds Group ARO map records for a list of user id's.
	 *
	 * @param	array	An array of user id's.
	 * @throws	The database error message.
	 */
	protected function addGroupAroMaps($aroIds)
	{
		if (is_array($aroIds)) {
			$db = $this->getDbo();

			foreach ($aroIds as $aroId) {
				// Get the gid of the user.
				$db->setQuery(
					'SELECT u.gid' .
					' FROM #__users AS u' .
					' INNER JOIN #__core_acl_aro AS aro ON aro.value = u.id' .
					' WHERE aro.id = '.(int) $aroId
				);
				$gid = $db->loadResult();
				if ($error = $db->getErrorMsg()) {
					throw new Excpetion($error);
				}

				if (empty($gid)) {
					// Set to registered.
					$gid = 18;
				}

				$db->setQuery(
					'INSERT INTO #__core_acl_groups_aro_map (group_id, aro_id) VALUES' .
					'('.(int) $gid.','.(int) $aroId.')'
				);
				if (!$db->query()) {
					throw new Excpetion($db->getErrorMsg());
				}
			}
		}
	}

	/**
	 * Removes a list of ARO records.
	 *
	 * @param	array	An array of ARO id's.
	 * @throws	The database error message.
	 */
	protected function removeAros($aroIds)
	{
		if (is_array($aroIds) && !empty($aroIds)) {
			$db = $this->getDbo();
			$db->setQuery(
				'DELETE FROM #__core_acl_groups_aro_map' .
				' WHERE aro_id IN ('.implode(',', $aroIds).')'
			);
			if (!$db->query()) {
				throw new Excpetion($db->getErrorMsg());
			}
		}
	}

	/**
	 * Removes a list of Group ARO map records.
	 *
	 * @param	array	An array of ARO id's.
	 * @throws	The database error message.
	 */
	protected function removeGroupAroMaps($aroIds)
	{
		if (is_array($aroIds) && !empty($aroIds)) {
			$db = $this->getDbo();
			$db->setQuery(
				'DELETE FROM #__core_acl_aro' .
				' WHERE id IN ('.implode(',', $aroIds).')'
			);
			if (!$db->query()) {
				throw new Excpetion($db->getErrorMsg());
			}
		}
	}
}