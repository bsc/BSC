<?php
/**
 * @version		$Id: view.html.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Category view.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserViewNote extends JView
{
	/**
	 * Override the display method for the view.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function display()
	{
		try
		{
			// Initialise variables.
			$uri			= JFactory::getUri();

			// Initialise view variables.
			$this->item		= $this->get('Item');
			$this->form		= $this->get('Form');
			$this->params	= $this->get('Params');

			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				throw new Exception(implode("\n", $errors), 500);
				return false;
			}

			$this->_addToolbar();
			parent::display();
		}
		catch (Exception $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
	}

	/**
	 * Display the toolbar.
	 *
	 * @return	void
	 * @since	1.0
	 */
	private function _addToolbar()
	{
		JRequest::setVar('hidemainmenu', 1);

		// Initialise variables.
		$user	= JFactory::getUser();
		$userId	= $user->get('id');

		JToolBarHelper::title(JText::_('COM_ARTOFUSER_VIEW_NOTE_TITLE'), 'logo');

		if (($this->item->checked_out && $this->item->checked_out == $userId) || !$this->item->checked_out) {
			JToolBarHelper::save('note.save');
			JToolBarHelper::apply('note.apply');
		}

		JToolBarHelper::cancel('note.cancel');
	}
}