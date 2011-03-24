<?php
/**
 * @version		$Id: user.php 528 2011-01-13 03:37:23Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
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
class ArtofUserControllerUser extends JController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('apply',		'save');
	}

	/**
	 * Proxy for getModel
	 */
	public function getModel($name = 'User')
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
	 * Method to add a new user.
	 *
	 * @return	void
	 */
	public function add()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Clear the edit information from the session.
		$app->setUserState('com_artofuser.edit.user.id',	null);
		$app->setUserState('com_artofuser.edit.user.data',	null);

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

		// Get the id of the record to edit.
		$pk =  (empty($pks) ? JRequest::getInt('user_id') : (int) array_pop($pks));

		// Get the current row id.
		$app->setUserState('com_artofuser.edit.user.id', $pk);

		// Get the model.
		$model = &$this->getModel();

		// Check that this is not a new user.
		if ($pk > 0) {
			$item = $model->getItem($pk);
		}

		// Check-out succeeded, push the new row id into the session.
		$app->setUserState('com_artofuser.edit.user.id',	$pk);
		$app->setUserState('com_artofuser.edit.user.data',	null);

		$this->setRedirect('index.php?option=com_artofuser&view=user&layout=edit');

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
		$previousId	= (int) $app->getUserState('com_artofuser.edit.user.id');

		// Get the model.
		$model = &$this->getModel();

		// Clear the edit information from the session.
		$app->setUserState('com_artofuser.edit.user.id',	null);
		$app->setUserState('com_artofuser.edit.user.data',	null);

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=users', false));
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
		$data['id'] = (int) $app->getUserState('com_artofuser.edit.user.id');

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy') {
			// Reset the ID and then treat the request as for Apply.
			$data['id']	= 0;
			$task		= 'apply';
		}

		/*if (isset($data['password1']) && isset($data['password2'])) {
			// Check the passwords match.
			if ($data['password1'] != $data['password2']) {
				// TODO: Handle this more gracefully.
				return JError::raiseError('Users_Error_Password_mismatch');
			}
			// Normally would unset the password2 field, but the JUser bind method requires it *sigh*.
		}*/

		// Get the form model object and validate it.
		$return	= $model->validate($data);

		// Check for validation errors.
		if ($return === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

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
			$app->setUserState('com_artofuser.edit.user.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=user&layout=edit', false));

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data)) {
			// Save the data in the session.
			$app->setUserState('com_artofuser.edit.user.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('COM_ARTOFUSER_Error_Save_failed', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=user&layout=edit', false));
			return false;
		}

		$this->setMessage(JText::_('JController_Save_success'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task) {
			case 'apply':
				// Set the row data in the session.
				$app->setUserState('com_artofuser.edit.user.id',	$model->getState('user.id'));
				$app->setUserState('com_artofuser.edit.user.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=user&layout=edit', false));
				break;

			case 'save2new':
				// Clear the row id and data in the session.
				$app->setUserState('com_artofuser.edit.user.id',	null);
				$app->setUserState('com_artofuser.edit.user.data',	null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=user&layout=edit', false));
				break;

			default:
				// Clear the row id and data in the session.
				$app->setUserState('com_artofuser.edit.user.id',	null);
				$app->setUserState('com_artofuser.edit.user.data',	null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_artofuser&view=users', false));
				break;
		}
	}
}