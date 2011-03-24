<?php
/**
 * @version		$Id: view.html.php 552 2011-01-18 23:05:49Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Categories view.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofcontent
 */
class ArtofUserViewNotes extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Override the display method for the view.
	 *
	 * @return	void
	 * @since	1.0
	 */
	public function display($tpl = null)
	{
		try
		{
			// Initialise view variables.
			$this->items		= $this->get('Items');
			$this->pagination	= $this->get('Pagination');
			$this->state		= $this->get('State');
			$this->canEditNote	= ArtofUserHelper::authorise('artofuser_edit_note');
			$this->canDeleteNote= ArtofUserHelper::authorise('artofuser_delete_note');

			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				throw new Exception(implode("\n", $errors), 500);
				return false;
			}

			$this->_setToolbar();
			parent::display($tpl);
		}
		catch (Exception $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
	}

	/**
	 * Display the toolbar
	 */
	private function _setToolbar()
	{
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_ARTOFUSER_VIEW_NOTES_TITLE'), 'logo');
		$bar->appendButton('Standard', 'publish', 'Publish', 'notes.publish', true);
		$bar->appendButton('Standard', 'unpublish', 'Unpublish', 'notes.unpublish', true);
		$bar->appendButton('Separator', 'divider');
		if ($this->canEditNote) {
			$bar->appendButton('Standard', 'edit', 'Edit', 'note.edit', true);
		}

		$bar->appendButton('Standard', 'new', 'New', 'note.add', false);

		if ($this->state->get('filter.published') == -2 && $this->canDeleteNote) {
			$bar->appendButton('Confirm', 'Are your sure', 'delete', 'Delete', 'notes.delete', true);
		}
		else {
			$bar->appendButton('Standard', 'trash', 'Trash', 'notes.trash', true);
		}

		JToolBarHelper::preferences('com_artofuser', 360, 800, 'COM_ARTOFUSER_TOOLBAR_OPTIONS');

		// We can't use the toolbar helper here because there is no generic popup button.
		JToolBar::getInstance('toolbar')
			->appendButton('Popup', 'help', 'COM_ARTOFUSER_TOOLBAR_ABOUT', 'index.php?option=com_artofuser&view=about&tmpl=component');
	}
}