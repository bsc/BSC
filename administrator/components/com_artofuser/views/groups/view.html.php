<?php
/**
 * @version		$Id: view.html.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserViewGroups extends JView
{
	protected $state;
	protected $items;
	protected $pagination;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->_setToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 */
	private function _setToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ARTOFUSER_View_Groups_Title'), 'logo');
		JToolBarHelper::custom('group.edit', 'edit.png', 'edit_f2.png', 'Edit', true);
		JToolBarHelper::custom('group.edit', 'new.png', 'new_f2.png', 'New', false);
		JToolBarHelper::deleteList('', 'groups.delete');
		JToolBarHelper::preferences('com_artofuser', 360, 800, 'COM_ARTOFUSER_TOOLBAR_OPTIONS');

		// We can't use the toolbar helper here because there is no generic popup button.
		JToolBar::getInstance('toolbar')
			->appendButton('Popup', 'help', 'COM_ARTOFUSER_TOOLBAR_ABOUT', 'index.php?option=com_artofuser&view=about&tmpl=component');
	}
}