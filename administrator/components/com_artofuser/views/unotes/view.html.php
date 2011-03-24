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
 * Categories view.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofcontent
 */
class ArtofUserViewUNotes extends JView
{
	protected $items;
	protected $state;
	protected $user;
	protected $tz;

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
			$this->items	= $this->get('Items');
			$this->state	= $this->get('State');
			$this->user		= $this->get('User');
			$this->tz		= JFactory::getApplication()->getCfg('offset');

			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				throw new Exception(implode("\n", $errors), 500);
				return false;
			}

			parent::display($tpl);
		}
		catch (Exception $e)
		{
			JError::raiseError(500, $e->getMessage());
		}
	}
}