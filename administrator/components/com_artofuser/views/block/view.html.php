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
 * View to input a block category and/or note.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserViewBlock extends JView
{
	protected $config;
	protected $form;
	protected $state;
	protected $users;

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
			// Initialise view variables.
			$this->state	= $this->get('State');
			$this->form		= $this->get('Form');
			$this->users	= $this->get('Users');
			$this->config	= JComponentHelper::getParams('com_artofuser');

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

		JToolBarHelper::title(JText::_('COM_ARTOFUSER_VIEW_BLOCK_TITLE'), 'logo');

		JToolBarHelper::apply('block.apply');
		JToolBarHelper::cancel('block.cancel');
	}
}