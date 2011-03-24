<?php
/**
 * @version		$Id: notes.php 552 2011-01-18 23:05:49Z eddieajau $
 * @copyright	Copyright (C) 2009 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

juimport('joomla.application.component.controlleradmin');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.1
 */
class ArtofUserControllerNotes extends JControllerAdmin
{
	/**
	 * Proxy for getModel
	 *
	 * @return	JModel
	 * @since	1.1
	 */
	function getModel()
	{
		return parent::getModel('Note', '', array('ignore_request' => true));
	}
}