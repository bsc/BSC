<?php
/**
 * @version		$Id: users.php 398 2010-11-08 01:16:53Z eddieajau $
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
class ArtofUserControllerUsers extends JController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('enable',	'block');
		$this->registerTask('disable',	'block');
	}

	/**
	 * Activates a user.
	 */
	function activate()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_token'));

		// Get items to remove from the request.
		$pks	= JRequest::getVar('cid', array(), '', 'array');
		$n		= count($pks);

		if (empty($pks)) {
			JError::raiseWarning(500, JText::_('JError_No_items_selected'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if ($model->activate($pks)) {
				$this->setMessage(JText::sprintf('COM_ARTOFUSER_N_users_activated', $n));
			}
			else {
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_artofuser&view=users');
	}

	/**
	 * Blocks or unblocks a user.
	 */
	function block()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JInvalid_token'));

		// Get items to remove from the request.
		$pks	= JRequest::getVar('cid', array(), '', 'array');
		$task	= $this->getTask();
		$n		= count($pks);

		// Determine the state value to set.
		switch ($task) {
			default:
			case 'block':
			case 'disable':
				$state = 1;
				break;
			case 'unblock':
			case 'enable':
				$state = 0;
				break;
		}

		if (empty($pks)) {
			JError::raiseWarning(500, JText::_('JError_No_items_selected'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if ($model->block($pks, $state)) {
				if ($state) {
					$this->setMessage(JText::sprintf('COM_ARTOFUSER_N_users_disabled', $n));
				}
				else {
					$this->setMessage(JText::sprintf('COM_ARTOFUSER_N_users_enabled', $n));
				}
			}
			else {
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_artofuser&view=users');
	}

	/**
	 * Removes an item.
	 *
	 * @return	void
	 * @since	1.0.6
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

		$this->setRedirect('index.php?option=com_artofuser&view=users');
	}

	/**
	 * Display the view
	 */
	function display()
	{
	}

	/**
	 * Proxy for getModel
	 */
	function getModel()
	{
		return parent::getModel('User', '', array('ignore_request' => true));
	}
}