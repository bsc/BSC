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
class ArtofUserViewGroup extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Bind the item data to the form object.
		if ($this->item) {
			$this->form->bind($this->item);
		}

		$this->_addToolbar();
		parent::display($tpl);

	}

	/**
	 * Display the toolbar.
	 */
	private function _addToolbar()
	{
		JRequest::setVar('hidemainmenu', 1);
		JToolBarHelper::title(JText::_('COM_ARTOFUSER_View_Group_Title'), 'logo');
		//JToolBarHelper::custom('save2new', 'new.png', 'new_f2.png', 'Save & New', false,  false);
		JToolBarHelper::save('group.save');
		JToolBarHelper::apply('group.apply');
		JToolBarHelper::cancel('group.cancel');
	}
}