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
class ArtofUserViewAnalyse extends JView
{
	protected $state;
	protected $data;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$state	= $this->get('State');
		$data	= $this->get('Data');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state',	$state);
		$this->assignRef('data',	$data);

		$this->_addToolbar();
		parent::display($tpl);
	}

	/**
	 * Display the toolbar
	 */
	private function _addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ARTOFUSER_View_Analyse_Title'), 'logo');
	}
}