<?php
/**
 * @version		$Id: unotes.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

juimport('joomla.application.component.modellist');
juimport('joomla.database.databasequery');

/**
 * Model for displaying all notes for a single user.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.0
 */
class ArtofUserModelUNotes extends JModelList
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.0
	 */
	protected function populateState()
	{
		$userId = JRequest::getInt('u_id');
		$this->setState('notes.user_id', $userId);

		$this->setState('list.limit', 0);
		$this->setState('list.start', 0);
		$this->setState('list.ordering', 'a.created_time');
		$this->setState('list.direction', 'DESC');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('notes.user_id');

		return parent::getStoreId($id);
	}

	/**
	 * Get the user information.
	 *
	 * @return	object
	 * @since	1.1
	 */
	public function getUser()
	{
		$userId = (int) $this->getState('notes.user_id');

		return JUser::getInstance($userId);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.0
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db			= $this->getDbo();
		$query		= new JDatabaseQuery;
		$section	= $this->getState('filter.section_id');

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__artofuser_notes AS a');

		// Join over the section
		$query->select('c.title AS category_title, c.image AS category_image');
		$query->leftJoin('`#__categories` AS c ON c.id = a.catid');

		// Join over the users for the note user.
		$query->select('u.name AS user_name');
		$query->leftJoin('#__users AS u ON u.id = a.user_id');

		// Filter on the user.
		$userId = (int) $this->getState('notes.user_id');
		$query->where('a.user_id = '.$userId);

		$query->where('a.published >= 0');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

//		echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}