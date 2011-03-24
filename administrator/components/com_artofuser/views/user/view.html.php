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
class ArtofUserViewUser extends JView
{
	protected $config;
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		$this->config	= JComponentHelper::getParams('com_artofuser');
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
		JToolBarHelper::title(JText::_('COM_ARTOFUSER_VIEW_USER_TITLE'), 'logo');
		JToolBarHelper::save('user.save');
		JToolBarHelper::apply('user.apply');
		JToolBarHelper::cancel('user.cancel');
	}

	/**
	 * Method to get custom fieldset that people might was to add (eg Kunena).
	 *
	 * @return	array
	 * @since	1.0.1
	 */
	protected function getCustomFieldsets()
	{
		// Get the dispatcher.
		$dispatcher	= JDispatcher::getInstance();

		// Include the user plugins.
		JPluginHelper::importPlugin('content');

		// Trigger the form preparation event.
		try
		{
			$results = $dispatcher->trigger('onContentPrepareForm', array('artofuser.user', $this->item));
		}
		catch (Exception $e)
		{
			// The plugin should throw an exception if it wants to stop the bus.
			JError::raiseError(500, $e->getMessage());
		}

		return $results;
	}
}