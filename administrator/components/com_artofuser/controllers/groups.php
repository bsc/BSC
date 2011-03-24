<?php
/**
 * @version		$Id: groups.php 397 2010-11-08 00:48:27Z eddieajau $
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
class ArtofUserControllerGroups extends JController
{
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
		return parent::getModel('Group', '', array('ignore_request' => true));
	}

	/**
	 * Removes an item
	 */
	function delete()
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
			if ($model->delete($pks)) {
				$this->setMessage(JText::sprintf('JSuccess_N_items_deleted', $n));
			}
			else {
				$this->setMessage($model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_artofuser&view=groups');
	}
}