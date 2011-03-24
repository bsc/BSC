<?php
/**
 * @version		$Id: controller.php 543 2011-01-15 04:39:43Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Component Controller
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserController extends JController
{
	/**
	 * Display the view
	 */
	function display()
	{
		// Initialise variables.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName		= JRequest::getWord('view', 'users');
		$vFormat	= $document->getType();
		$lName		= JRequest::getWord('layout', 'default');

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			switch ($vName)
			{
				default:
					$model	= $this->getModel($vName);
					break;
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();

			// Load the submenu.
//			ArtofUserHelper::addSubmenu($vName);
		}
	}
}