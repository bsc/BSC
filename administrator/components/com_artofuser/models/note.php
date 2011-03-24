<?php
/**
 * @version		$Id: note.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

juimport('joomla.application.component.modeladmin');
juimport('joomla.database.databasequery');

/**
 * Category model.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserModelNote extends JModelAdmin
{
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
//		$user = JFactory::getUser();
//		return $user->authorise('core.delete', $this->option);
		return ArtofUserHelper::authorise('artofuser_delete_note');
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 *
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
//		$user = JFactory::getUser();
//		return $user->authorise('core.edit.state', $this->option);
		return ArtofUserHelper::authorise('artofuser_edit_note');
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.1
	 */
	protected function populateState()
	{
		$pk = JRequest::getInt('id');
		$this->setState('note.id', $pk);

		$userId = JRequest::getInt('u_id');
		$this->setState('note.user_id', $userId);
	}

	/**
	 * Method to get the form object.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialize variables.
		$app	= JFactory::getApplication();
		$false	= false;

		// Get the form.
		juimport('jxtended.form.form');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		$form = JForm::getInstance('note', 'jform', true, array('array' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return $false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('artofuser.edit.note.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}
		else {
			$form->bind($this->getItem());
		}

		return $form;
	}

	/**
	 * Method to get a note.
	 *
	 * @param	integer	An optional id of the object to get, otherwise the id from the model state is used.
	 * @return	mixed	Category data object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk		= (int) ($pk ? $pk : $this->getState('note.id'));
		$key	= 'note.'.$pk;

		// Check if the item has already been cached.
		if (isset($this->cache[$key])) {
			return $this->cache[$key];
		}

		$this->cache[$key] = parent::getItem($pk);

		$userId = $this->getState('note.user_id');
		if ($userId) {
			$this->cache[$key]->user_id = $userId;
		}

		return $this->cache[$key];
	}

	/**
	 * Method to get the params for this model.
	 *
	 * @return	JRegistry
	 * @since	1.1
	 */
	public function getParams()
	{
		// Initialise variables.
		$params	= JComponentHelper::getParams('com_artofuser');

		// Add shortcuts for access controls.
//		$params->set('access-edit', SdHelper::authorise('core_manage'));

		return $params;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param	string	$name		The table name. Optional.
	 * @param	string	$prefix		The class prefix. Optional.
	 * @param	array	$options	Configuration array for model. Optional.
	 *
	 * @return	object	The table
	 */
	public function getTable($name = 'Note', $prefix = 'ArtofUserTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 *
	 * @return	boolean	True on success.
	 */
	public function save($data)
	{
		// Initialise variables.
		$pk		= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('note.id');
		$table	= $this->getTable();
		$isNew	= empty($pk);

		if (!$table->bind($data)) {
			$this->setError($table->getError());

			return false;
		}

		// JTableCategory doesn't bind the params, so we need to do that by hand.
		if (isset($data['params']) && is_array($data['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($data['params']);
			$table->params = $registry->toString();
			// This will give us INI format.
		}

		if (!$table->check()) {
			$this->setError($table->getError());

			return false;
		}

		// OnSave plugins
//		$dispatcher	= JDispatcher::getInstance();
//		JPluginHelper::importPlugin('content');
//
//		$result = $dispatcher->trigger('onContentBeforeSave', array(&$table, $isNew));
//		if (in_array(false, $result)) {
//			foreach ($dispatcher->getErrors() as $error)
//			{
//				$this->setError($error);
//			}
//
//			return false;
//		}

		if (!$table->store()) {
			$this->setError($table->getError());

			return false;
		}

//		$result = $dispatcher->trigger('onContentAfterSave', array(&$table, $isNew));
//		if (in_array(false, $result)) {
//			foreach ($dispatcher->getErrors() as $error)
//			{
//				$this->setError($error);
//			}
//
//			return false;
//		}

		$this->setState('note.id', $table->id);

		return true;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	array	The form data.
	 * @return	mixed	Array of filtered data if valid, false otherwise.
	 * @since	1.1
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