<?php
/**
 * @version		$Id: note.php 528 2011-01-13 03:37:23Z eddieajau $
 * @copyright	Copyright (C) 2009 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserTableNote extends JTable
{
	/**
	 * @var int
	 */
	var $id = null;

	/**
	 * @var int
	 */
	var $user_id = null;

	/**
	 * @var int
	 */
	var $catid = null;

	/**
	 * @var string
	 */
	var $subject = null;

	/**
	 * @var string
	 */
	var $body = null;

	/**
	 * @var int
	 */
	var $checked_out = null;

	/**
	 * @var string
	 */
	var $checked_out_time = null;

	/**
	 * @var int
	 */
	var $created_user_id = null;

	/**
	 * @var string
	 */
	var $created_time = null;

	/**
	 * @var int
	 */
	var $modified_user_id = null;

	/**
	 * @var string
	 */
	var $modified_time = null;

	/**
	 * @var string
	 */
	var $review_time = null;

	/*
	 * Constructor
	 *
	 * @param	object	$db	Database object
	 *
	 * @returns	ArtofUserTableNote
	 * @since	1.1
	 */
	function __construct(&$db)
	{
		parent::__construct('#__artofuser_notes', 'id', $db);
	}

	/**
	 * Overload the store method for the Weblinks table.
	 *
	 * @param	boolean	$updateNulls	Toggle whether null values should be updated.
	 *
	 * @return	boolean	True on success, false on failure.
	 * @since	1.0
	 */
	public function store($updateNulls = false)
	{
		// Initialiase variables.
		$date	= JFactory::getDate()->toMySQL();
		$userId	= JFactory::getUser()->get('id');

		if (empty($this->id)) {
			// New record.
			$this->created_time		= $date;
			$this->created_user_id	= $userId;
		}
		else {
			// Existing record.
			$this->modified_time	= $date;
			$this->modified_user_id	= $userId;
		}

		// Attempt to store the data.
		return parent::store($updateNulls);
	}
}