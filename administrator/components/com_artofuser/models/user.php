<?php
/**
 * @version		$Id: user.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

juimport('joomla.application.component.model16');
juimport('joomla.database.databasequery');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserModelUser extends JModel16
{
	/**
	 * Auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function populateState()
	{
		$app = &JFactory::getApplication('administrator');

		// Load the User state.
		if (!($pk = (int) $app->getUserState('com_artofuser.edit.user.id'))) {
			$pk = JRequest::getInt('user_id');
		}
		$this->setState('user.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_artofuser');
		$this->setState('params', $params);
	}

	/**
	 * Method to activate a user.
	 *
	 * @param	array	An array of group ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function activate($pks = array())
	{
		// Initialise variables.
		$me		= JFactory::getUser();
		$myId	= $me->get('id');
		JArrayHelper::toInteger($pks);

		// Loop through each user so that appropriate triggers are fired.
		foreach ($pks as $pk) {
			if ($pk == $myId) {
				// Ignore changing this setting for me.
				JError::raiseWarning(500, 'COM_ARTOFUSER_Warning_Cannot_alter_me');
				continue;
			}
			$user = JUser::getInstance($pk);
			$user->set('activation', '');
			$user->set('block', 0);

			// Save the user data.
			if (!$user->save()) {
				$this->setError($user->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to block/unblock a user.
	 *
	 * @param	array	An array of group ids.
	 * @param	int		The blocked state to set.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function block($pks = array(), $state = 1)
	{
		// Initialise variables.
		$me		= JFactory::getUser();
		$myId	= $me->get('id');
		JArrayHelper::toInteger($pks);

		// Loop through each user so that appropriate triggers are fired.
		foreach ($pks as $pk){
			if ($pk == $myId) {
				// Ignore changing this setting for me.
				JError::raiseWarning(500, 'COM_ARTOFUSER_Warning_Cannot_alter_me');
				continue;
			}
			$user = JUser::getInstance($pk);
			$user->set('block', $state);

			// Save the user data.
			if (!$user->save()) {
				$this->setError($user->getError());
				return false;
			}
		}

		return true;
	}

	/**
	 * Method to delete groups.
	 *
	 * @param	array	An array of group ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete($pks = array())
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		$acl	= JFactory::getAcl();
		$me		= JFactory::getUser();
		$myId	= $me->get('id');
		JArrayHelper::toInteger($pks);

		foreach ($pks as $pk)
		{
			$user		= JUser::getInstance($pk);
			$userGid	= (int) $user->get('gid');

			$aroId 		= (int) $acl->get_object_id('users', $pk, 'ARO');
			$group	 	= $acl->get_group_data($userGid, 'ARO');
			$groupName	= strtolower($group[3]);

			if ($groupName == 'super administrator') {
				$this->setError(JText::_('COM_ARTOFUSER_Error_Cannot_delete_super_admin'));
				return false;
			}
			else if ($pk == $myId) {
				$this->setError(JText::_('COM_ARTOFUSER_Error_Cannot_delete_self'));
				return false;
			}
			else if ($groupName == 'administrator' && $myId == 24) {
				$this->setError(JText::_('WARNDELETE'));
				return false;
			}

			// Delete the user.
			if (!$user->delete()) {
				$this->setError($user->getError());
				return false;
			}

			// Log the user out.
			$options = array(
				'clientid' => array(0, 1)
			);
			$app->logout($pk, $options);
		}

		return true;
	}

	/**
	 * Method to get the form object.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function &getForm()
	{
		// Initialize variables.
		$app	= JFactory::getApplication();
		$false	= false;

		// Get the form.
		juimport('jxtended.form.form');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		$form = JForm::getInstance('user', 'jform', true, array('array' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return $false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('artofuser.edit.user.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * Method to get a row.
	 *
	 * @param	integer	An optional id of the object to get, otherwise the id from the model state is used.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? (int) $pk : (int)$this->getState('user.id');

		// Get a level row instance.
		$user = JUser::getInstance($pk);

		$user->params = $user->_params->toArray();

		// Convert the JTable to a clean JObject.
		$result = JArrayHelper::toObject($user->getProperties(1), 'JObject');
		unset($result->password);

		// Find all the groups the user is in.
		$db = $this->getDbo();
		$query = new JDatabaseQuery;
		$query->select('DISTINCT map.group_id');
		$query->from('#__core_acl_groups_aro_map AS map');
		$query->join('INNER', '#__core_acl_aro AS aro ON aro.id = map.aro_id');
		$query->where('aro.value = '.$pk);
		$db->setQuery((string) $query);
		$result->groups = (array) $db->loadResultArray();

		if ($error = $db->getErrorMsg()) {
			$this->setError($error);
			return false;
		}

		// Fix up default user group.
		if (empty($result->gid)) {
			$params = JComponentHelper::getParams('com_users');
			$newGroup = $params->get('new_usertype');

			// Unfortunately, the new usertype is stored as text, so we have to look it up.
			$query = new JDatabaseQuery;
			$query->select('id');
			$query->from('#__core_acl_aro_groups');
			$query->where('value = '.$db->quote($newGroup));
			$db->setQuery((string) $query);
			$result->gid = $db->loadResult();

			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}
		}

		// Get the blocked category.
		$query = new JDatabaseQuery;
		$query->select('b.catid')
			->from('`#__artofuser_blocked` AS b')
			->where('b.user_id = '.$pk);

		$db->setQuery($query);
		$catId = $db->loadResult();

		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());

			return false;
		}

		$result->block_catid = $catId;

		return $result;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 */
	public function save($data)
	{
		// Initialise variables.
		$pk		= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('group.id');
		$acl	= JFactory::getACL();
		$me		= JFactory::getUser();
		$user	= new JUser($pk);
		$oldGid	= $user->get('gid');
		$config	= JComponentHelper::getParams('com_artofuser');

		if (!$user->bind($data)) {
			$this->setError($user->getError());
			return false;
		}

		$myId		= (int) $me->get('id');
		$myGid		= (int) $me->get('gid');
		$userId		= (int) $user->get('id');
		$userGid	= (int) $user->get('gid');
		$blocked	= (int) $user->get('block');
		$aroId 		= (int) $acl->get_object_id('users', $userId, 'ARO');
		$group	 	= $acl->get_group_data($userGid, 'ARO');
		$groupName	= strtolower($group[3]);

		// Sanity checks per Joomla's com_users.
		if ($userId == $myId && $blocked) {
			$this->setError('COM_ARTOFUSER_Error_Cannot_block_self');

			return false;
		}
		else if ($groupName == 'super administrator' && $blocked == 1) {
			$this->setError('COM_ARTOFUSER_Error_Cannot_block_super_admin');

			return false;
		}
		else if ($groupName == 'administrator' && $myGid == 24 && $blocked == 1) {
			$this->setError('COM_ARTOFUSER_Error_Cannot_block_this_user');

			return false;
		}
		else if ($groupName == 'super administrator' && $myGid != 25) {
			$this->setError('COM_ARTOFUSER_Error_Cannot_edit_this_user');

			return false;
		}

		// Are we dealing with a new user which we need to create?
		$isNew = empty($userId);

		if (!$isNew) {
			// If the user was a Super Admin, check we still have one left.
			if ($userGid != $oldGid && $oldGid == 25) {
				// count number of active super admins
				$db = $this->getDbo();
				$db->setQuery(
					'SELECT COUNT(id)'
					. ' FROM #__users'
					. ' WHERE gid = 25'
					. ' AND block = 0'
				);
				$count = $db->loadResult();

				if (empty($count)) {
					$this->setError('COM_ARTOFUSER_Error_Must_have_super_admin');
					return false;
				}
			}
		}

		// Save the user data.
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}

		if ($isNew) {
			$userId		= $user->get('id');
			$xGroups	= array();
		}
		else {
			$xGroups	= $acl->get_object_groups($aroId, 'aro');
		}

		// Attend to the user groups.
		$groupIds	= (array) $data['groups'];
		array_push($groupIds, $userGid);
		$groupIds	= array_unique($groupIds);

		// Delete from the existing groups.
		foreach ($xGroups as $gid)
		{
			if (!$acl->del_group_object($gid, 'users', $userId, 'aro')) {
				$this->setError($acl->_debugLog);
				return false;
			}
		}

		// put into the new groups
		foreach ($groupIds as $gid)
		{
			if (!$acl->add_group_object($gid, 'users', $userId, 'aro')) {
				$this->setError($acl->_debugLog);
				return false;
			}
		}

		try
		{
			// Map the blocked category.
			JModel::getInstance('Block', 'ArtofUserModel')
				->setCategory((int) $data['block_catid'], array($user->id));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add a note.
		if (!empty($data['note'])) {
			$note = JModel::getInstance('Note', 'ArtofUserModel');
			$nres = $note->save(
				array(
					'user_id'	=> $userId,
					'body'		=> $data['note'] == strip_tags($data['note']) ? '<p>'.$data['note'].'</p>' : $data['note'],
				)
			);

			if (!$nres) {
				$this->setError($model->getError());
				return false;
			}
		}

		// Send emails
		if ($isNew) {
			$app		= JFactory::getApplication();
			$MailFrom	= $app->getCfg('mailfrom');
			$FromName	= $app->getCfg('fromname');
			$SiteName	= $app->getCfg('sitename');

			$adminEmail = $me->get('email');
			$adminName	= $me->get('name');

			$subject = JText::_('COM_ARTOFUSER_NEW_USER_MESSAGE_SUBJECT');
			if ($config->def('mail_pass', 1)) {
				$message = sprintf (JText::_('COM_ARTOFUSER_NEW_USER_MESSAGE'), $user->get('name'), $SiteName, JURI::root(), $user->get('username'), $user->password_clear);
			}
			else {
				$message = sprintf (JText::_('COM_ARTOFUSER_NEW_USER_MESSAGE_NOPASS'), $user->get('name'), $SiteName, JURI::root(), $user->get('username'));
			}

			if ($MailFrom != '' && $FromName != '') {
				$adminName 	= $FromName;
				$adminEmail = $MailFrom;
			}

			JUtility::sendMail($adminEmail, $adminName, $user->get('email'), $subject, $message);
		}

		// If updating self, load the new user object into the session
		if ($userId == $myId) {
			// Get the user group from the ACL
			$grp = $acl->getAroGroup($userId);

			// Mark the user as logged in
			$user->set('guest', 0);
			$user->set('aid', 1);

			// Fudge Authors, Editors, Publishers and Super Administrators into the special access group
			if ($acl->is_group_child_of($grp->name, 'Registered')      ||
			    $acl->is_group_child_of($grp->name, 'Public Backend'))    {
				$user->set('aid', 2);
			}

			// Set the usertype based on the ACL group name
			$user->set('usertype', $grp->name);

			$session = JFactory::getSession();
			$session->set('user', $user);
		}

		$this->setState('user.id', $userId);

		return true;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	array	The form data.
	 * @return	mixed	Array of filtered data if valid, false otherwise.
	 * @since	1.0
	 */
	public function validate($data)
	{
		// Get the form.
		$form = &$this->getForm();

		// Check for an error.
		if ($form === false) {
			return false;
		}

		// Filter and validate the form data.
		$data	= $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}
}