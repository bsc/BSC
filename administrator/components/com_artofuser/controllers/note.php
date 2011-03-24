<?php
/**
 * @version		$Id: note.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

juimport('joomla.application.component.controllerform');

/**
 * Category Subcontroller.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserControllerNote extends JControllerForm
{
	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param	array	An array of input data.
	 *
	 * @return	boolean
	 * @since	1.1
	 */
	protected function allowAdd($data = array())
	{
//		return JFactory::getUser()->authorise('core.create', $this->option);
		return true;
	}

	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param	array	An array of input data.
	 * @param	string	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.1
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
//		return JFactory::getUser()->authorise('core.edit', $this->option);
		return ArtofUserHelper::authorise('artofuser_edit_note');
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param	int		$recordId	The primary key id for the item.
	 * @param	string	$key		The name of the primary key variable.
	 *
	 * @return	string	The arguments to append to the redirect URL.
	 * @since	1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $key = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $key);

		$userId = JRequest::getInt('u_id');
		if ($userId) {
			$append .= '&u_id='.$userId;
		}

		return $append;
	}

}