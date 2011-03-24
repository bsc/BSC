<?php
/**
 * @version		$Id: group.php 398 2010-11-08 01:16:53Z eddieajau $
 * @copyright	Copyright (C) 2009 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserControllerGroup extends JController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('save2copy',	'save');
		$this->registerTask('save2new',		'save');
		$this->registerTask('apply',		'save');
	}

	/**
	 * Proxy for getModel
	 */
	public function getModel($name = 'Group')
	{
		return parent::getModel($name, '', array('ignore_request' => true));
	}

	/**
	 * Display the view
	 */
	public function display()
	{
	}

	/**
	 * Method to add a new Group.
	 *
	 * @return	void
	 */
	public function add()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Clear the edit information from the session.
		$app->setUserState('com_artofuser.edit.group.id',	null);
		$app->setUserState('com_artofuser.edit.group.data',	null);

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=group&layout=edit', false));
	}

	/**
	 * Method to edit a object
	 *
	 * Sets object ID in the session from the request, checks the item out, and then redirects to the edit page.
	 *
	 * @return	void
	 */
	public function edit()
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		$pks	= JRequest::getVar('cid', array(), '', 'array');

		// Get the id of the group to edit.
		$pk =  (empty($pks) ? JRequest::getInt('group_id') : (int) array_pop($pks));

		// Get the current row id.
		$app->setUserState('com_artofuser.edit.group.id', $pk);

		// Get the model.
		$model = &$this->getModel();

		// Check that this is not a new group.
		if ($pk > 0) {
			$item = $model->getItem($pk);
		}

		// Check-out succeeded, push the new row id into the session.
		$app->setUserState('com_artofuser.edit.group.id',	$pk);
		$app->setUserState('com_artofuser.edit.group.data',	null);

		$this->setRedirect('index.php?option=com_artofuser&view=group&layout=edit');

		return true;
	}

	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @return	void
	 */
	public function cancel()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the previous id (if any) and the current id.
		$previousId	= (int) $app->getUserState('com_artofuser.edit.group.id');

		// Get the model.
		$model = &$this->getModel();

		// Clear the edit information from the session.
		$app->setUserState('com_artofuser.edit.group.id',	null);
		$app->setUserState('com_artofuser.edit.group.data',	null);

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=groups', false));
	}

	/**
	 * Save the record
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken();

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel();
		$task	= $this->getTask();
		$offset	= $app->getCfg('offset');

		// Get the posted values from the request.
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Populate the row id from the session.
		$data['id'] = (int) $app->getUserState('com_artofuser.edit.group.id');

		unset($data['created_time']);
		unset($data['created_user_id']);
		unset($data['modified_time']);
		unset($data['modified_user_id']);

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy') {
			// Reset the ID and then treat the request as for Apply.
			$data['id']	= 0;
			$task		= 'apply';
		}

		// Get the form model object and validate it.
		$form	= &$model->getForm('model');
		$form->filter($data);
		$result	= $form->validate($data);

		if (JError::isError($result)) {
			// Get the validation messages.
			$errors	= $form->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				}
				else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_artofuser.edit.group.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=group&layout=edit', false));
			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data)) {
			// Save the data in the session.
			$app->setUserState('com_artofuser.edit.group.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JError_Save_failed', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=group&layout=edit', false));
			return false;
		}

		$this->setMessage(JText::_('JController_Save_success'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task) {
			case 'apply':
				// Set the row data in the session.
				$app->setUserState('com_artofuser.edit.group.id',	$model->getState('group.id'));
				$app->setUserState('com_artofuser.edit.group.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=group&layout=edit', false));
				break;

			case 'save2new':
				// Clear the row id and data in the session.
				$app->setUserState('com_artofuser.edit.group.id',	null);
				$app->setUserState('com_artofuser.edit.group.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=group&layout=edit', false));
				break;

			default:
				// Clear the row id and data in the session.
				$app->setUserState('com_artofuser.edit.group.id',	null);
				$app->setUserState('com_artofuser.edit.group.data',	null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=groups', false));
				break;
		}
	}

	/**
	 * Removes an item
	 */
	public function delete()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_token'));

		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (empty($cid)) {
			JError::raiseWarning(500, JText::_('Select an item to delete'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if (!$model->delete($cid)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_artofuser&view=groups');
	}
}