<?php
/**
 * @version		$Id: block.php 544 2011-01-15 04:40:24Z eddieajau $
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
 * @since		1.1
 */
class ArtofUserModelBlock extends JModel16
{
	/**
	 * Auto-populate the model state.
	 *
	 * @return	void
	 * @since	1.1
	 */
	protected function populateState()
	{
		$ids = JRequest::getVar('cid', array());

		JArrayHelper::toInteger($ids);

		$this->setState('list.user_ids', $ids);
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
		$form = JForm::getInstance('block', 'jform', true);

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
	 * Get the names of the user we are blocking.
	 *
	 * @return	array
	 * @since	1.1
	 * @throws	Exception on database error.
	 */
	public function getUsers()
	{
		$userIds = $this->getState('list.user_ids');

		if (empty($userIds)) {
			return array();
		}

		JArrayHelper::toInteger($userIds);

		$db	= $this->getDbo();
		$query = new JDatabaseQuery;

		$query->select('id, name')
			->from('#__users')
			->where('id IN ('.implode(',', $userIds).')')
			->order('name')
			;

		$db->setQuery($query);

		$users = $db->loadObjectList();

		if ($db->getErrorNum()) {
			throw new Exception($db->getErrorMsg());
		}

		return $users;
	}

	/**
	 * Sets the block state for the user list.
	 *
	 * @param	array	$userIds	The user Id's.
	 *
	 * @return	void
	 * @throws	Exception on database error or invalid data.
	 */
	public function setBlock($userIds)
	{
		// Validate data.
		if (empty($userIds)) {
			throw new Exception(JText::_('COM_ARTOFUSER_ERROR_EMPTY_USER_IDS'));
		}

		// Get the model.
		$model		= JModel::getInstance('User', 'ArtofUserModel');
		JArrayHelper::toInteger($userIds);

		// Remove the items.
		if (!$model->block($userIds, 1)) {
			throw new Exception($model->getError());
		}
	}

	/**
	 * Sets the block category for the user list.
	 *
	 * @param	int		$categoryId	The category Id's.
	 * @param	array	$userIds	The user Id's.
	 *
	 * @return	void
	 * @throws	Exception on database error or invalid data.
	 */
	public function setCategory($categoryId, $userIds)
	{
		// Validate data.
		if (empty($userIds)) {
			throw new Exception(JText::_('COM_ARTOFUSER_ERROR_EMPTY_USER_IDS'));
		}

		$tuples = array();
		foreach ($userIds as $userId)
		{
			$tuples[] = '('.(int) $userId.','.(int) $categoryId.')';
		}

		$db	= JFactory::getDbo();
		$db->setQuery(
			'REPLACE INTO `#__artofuser_blocked` (`user_id`, `catid`) VALUES '.
			implode(',', $tuples)
		);

		if (!$db->query()) {
			throw new Exception($db->getErrorMsg());
		}
	}

	/**
	 * Sets the block note for the user list.
	 *
	 * @param	int		$note		The category Id's.
	 * @param	date	$reviewTime	The review time for the note.
	 * @param	array	$userIds	The user Id's.
	 *
	 * @return	void
	 * @throws	Exception on database error or invalid data.
	 */
	public function setNote($note, $reviewTime, $userIds)
	{
		// Validate data.
		if (empty($userIds)) {
			throw new Exception(JText::_('COM_ARTOFUSER_ERROR_EMPTY_USER_IDS'));
		}

		if (empty($note) || trim(strip_tags($note)) == '') {
			throw new Exception(JText::_('COM_ARTOFUSER_ERROR_EMPTY_NOTE'));
		}

		$model = JModel::getInstance('Note', 'ArtofUserModel');

		foreach ($userIds as $userId)
		{
			$nres = $model->save(
				array(
					'user_id'		=> (int) $userId,
					'body'			=> ($note == strip_tags($note)) ? '<p>'.$note.'</p>' : $note,
					'review_time'	=> $reviewTime,
				)
			);

			if (!$nres) {
				throw new Exception($model->getError());
			}
		}
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