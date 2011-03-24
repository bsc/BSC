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
class ArtofUserViewUsers extends JView
{
	protected $config;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->config		= JComponentHelper::getParams('com_artofuser');

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
		$bar	= JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_ARTOFUSER_View_Users_Title'), 'logo');
		$bar->appendButton('Standard', 'publish', 'Enable', 'users.enable', true);

		if ($this->config->get('block_req_cat') || $this->config->get('block_req_note')) {
			$bar->appendButton('Standard', 'unpublish', 'Disable', 'block.display', true);
		}
		else {
			$bar->appendButton('Standard', 'unpublish', 'Disable', 'users.disable', true);
		}

		$bar->appendButton('Standard', 'apply', 'COM_ARTOFUSER_Toolbar_Activate_user', 'users.activate', true);
		$bar->appendButton('Separator', 'divider');
		$bar->appendButton('Standard', 'edit', 'Edit', 'user.edit', true);
		$bar->appendButton('Standard', 'new', 'New', 'user.edit', false);
		$bar->appendButton('Confirm', 'Are your sure', 'delete', 'Delete', 'users.delete', true);

		JToolBarHelper::preferences('com_artofuser', 360, 800, 'COM_ARTOFUSER_TOOLBAR_OPTIONS');

		// We can't use the toolbar helper here because there is no generic popup button.
		JToolBar::getInstance('toolbar')
			->appendButton('Popup', 'help', 'COM_ARTOFUSER_TOOLBAR_ABOUT', 'index.php?option=com_artofuser&view=about&tmpl=component');
	}
}