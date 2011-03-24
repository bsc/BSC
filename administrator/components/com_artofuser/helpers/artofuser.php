<?php
/**
 * @version		$Id: artofuser.php 549 2011-01-18 22:13:14Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Content component helper.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_ARTOFUSER_SUBMENU_USERS'),
			'index.php?option=com_artofuser&view=users',
			$vName == 'users'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ARTOFUSER_SUBMENU_GROUPS'),
			'index.php?option=com_artofuser&view=groups',
			$vName == 'groups'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ARTOFUSER_SUBMENU_NOTES'),
			'index.php?option=com_artofuser&view=notes',
			$vName == 'notes'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_ARTOFUSER_SUBMENU_NOTE_CATEGORIES'),
			'index.php?option=com_categories&section=notes',
			$vName == 'notes'
		);
//		JSubMenuHelper::addEntry(
//			JText::_('COM_ARTOFUSER_Submenu_Tools'),
//			'index.php?option=com_artofuser&view=tools',
//			$vName == 'dashboard'
//		);
	}

	/**
	 * Authorise access to this component.
	 *
	 * @param	string	$action	Check authorisation for a particular action.
	 *
	 * @return	boolean	True if this user can access this component.
	 * @since	1.1
	 */
	public function authorise($action = 'core_manage')
	{
		// Initialise variables.
		$user	= JFactory::getUser();

		// Always allow the Super Admin access.
		if ($user->get('gid') == 25) {
			return true;
		}

		// We only want to check this once for the session,
		// but if the configuration is changed we want to reset it.
		$app		= JFactory::getApplication();
		$config		= JComponentHelper::getParams('com_artofuser');
		$userGroups	= $app->getUserState('artofjoomla.usergroups');
		$hash		= md5('com_artofuser.config.md5:'.serialize($config));

		if (empty($userGroups) || $hash != $app->getUserState('com_artofuser.config.md5')) {
			$userId	= (int) $user->get('id');
			$db		= JFactory::getDbo();

			// Lookup the correct user group mapping.
			$db->setQuery(
				'SELECT group_id' .
				' FROM #__core_acl_groups_aro_map AS map' .
				' JOIN #__core_acl_aro AS aro ON aro.id = map.aro_id' .
				' WHERE aro.value = '.$db->quote($userId)
			);
			$userGroups = $db->loadResultArray();
			$error		= $db->getErrorMsg();

			if ($error) {
				JError::raiseWarning(500, $error);

				return false;
			}

			// Set the hash in the session so we can detect a change later on.
			$app->setUserState('com_artofuser.config.md5', $hash);

			// Set the usergroups for the user in the session so we don't have to look it up again.
			$app->setUserState('artofjoomla.usergroups', $userGroups);
		}

		// Now we have the users groups.
		$actionGroups	= $config->get($action);
		if (!is_array($actionGroups) && is_scalar($actionGroups)) {
			$actionGroups = array($actionGroups);
		}
		else {
			$actionGroups = array();
		}

		$intersect		= array_intersect($actionGroups, $userGroups);

		return !empty($intersect);
	}

	/**
	 * Get a list of user groups.
	 */
	public function getUsergroupOptions()
	{
		$model = JModel::getInstance('Groups', 'ArtofUserModel', array('ignore_request' => true));

		$model->setState('filter.tree',			'1');
		$model->setState('filter.parent_id',	28);
		$model->setState('list.select',			'a.id AS value, a.name AS text');
		$options = $model->getItems();

		foreach ($options as $i => $option) {
			$options[$i]->text = str_pad($option->text, strlen($option->text) + 2*$option->level, '- ', STR_PAD_LEFT);
		}

		return $options;
	}

	/**
	 * Get the list of block options for filtering.
	 */
	public static function  getBlockedOptions()
	{
		$options = array(
			JHtml::_('select.option', 0, JText::_('COM_ARTOFUSER_state_enabled')),
			JHtml::_('select.option', 1, JText::_('COM_ARTOFUSER_state_disabled'))
		);
		return $options;
	}

	/**
	 * Get the list of block options for filtering.
	 */
	public static function  getActivationOptions()
	{
		$options = array(
			JHtml::_('select.option', 0, JText::_('COM_ARTOFUSER_state_pending_activation')),
			JHtml::_('select.option', 1, JText::_('COM_ARTOFUSER_state_activated'))
		);
		return $options;
	}

	/**
	 * Get a list of date range filters
	 */
	public static function getRangeOptions()
	{
		$options = array(
			JHtml::_('select.option', 'today', JText::_('COM_ARTOFUSER_option_range_today')),
			JHtml::_('select.option', 'past_week', JText::_('COM_ARTOFUSER_option_range_past_week')),
			JHtml::_('select.option', 'past_1month', JText::_('COM_ARTOFUSER_option_range_past_1month')),
			JHtml::_('select.option', 'past_3month', JText::_('COM_ARTOFUSER_option_range_past_3month')),
			JHtml::_('select.option', 'past_6month', JText::_('COM_ARTOFUSER_option_range_past_6month')),
			JHtml::_('select.option', 'past_year', JText::_('COM_ARTOFUSER_option_range_past_year')),
			JHtml::_('select.option', 'post_year', JText::_('COM_ARTOFUSER_option_range_post_year')),
		);
		return $options;
	}

	/**
	 * Get the list of content sections.
	 *
	 * @return	array
	 * @since	1.0
	 */
	public static function getPublishedOptions()
	{
		$options = array(
			JHtml::_('select.option', -2, JText::_('JTRASHED')),
		);

		return $options;
	}

	/**
	 * Get the list of content sections.
	 *
	 * @return	array
	 * @since	1.0
	 */
	public static function getCategoryOptions()
	{
		$db = JFactory::getDbo();
		$query = new JDatabaseQuery;

		$query->select('c.id AS value, c.title AS text')
			->from('#__categories AS c')
			->where('section = '.$db->quote('com_artofuser_notes'))
			->order('c.title')
			;

		$db->setQuery($query);

		$options = $db->loadObjectList();

		return $options;
	}
}