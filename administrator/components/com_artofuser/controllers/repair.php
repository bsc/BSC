<?php
/**
 * @version		$Id: repair.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * The Repair Controller.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserControllerRepair extends JController
{
	/**
	 */
	public function aros()
	{
		// Get the setup model.
		$repair = $this->getModel('Repair');

		// Attempt to run the manual install routine.
		if (!$repair->aros()) {
			$this->setMessage(JText::sprintf('COM_ARTOFUSER_Error_Repair_failed', $model->getError()), 'notice');
		}
		else {
			$this->setMessage(JText::_('COM_ARTOFUSER_Success_Aros_repaired'));
		}

		// Set the redirect.
		$this->setRedirect('index.php?option=com_artofuser&view=analyse');
	}

	/**
	 */
	public function groupAroMaps()
	{
		// Get the setup model.
		$repair = $this->getModel('Repair');

		// Attempt to run the manual install routine.
		if (!$repair->groupAroMaps()) {
			$this->setMessage(JText::sprintf('COM_ARTOFUSER_Error_Repair_failed', $model->getError()), 'notice');
		}
		else {
			$this->setMessage(JText::_('COM_ARTOFUSER_Success_Group_Aro_Maps_repaired'));
		}

		// Set the redirect.
		$this->setRedirect('index.php?option=com_artofuser&view=analyse');
	}
}