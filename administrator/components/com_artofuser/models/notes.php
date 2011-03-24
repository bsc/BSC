<?php
/**
 * @version		$Id: notes.php 546 2011-01-15 05:00:00Z eddieajau $
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
 * Categories model.
 *
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.0
 */
class ArtofUserModelNotes extends JModelList
{
	/**
	 * Class constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.
	 *
	 * @return	ArtofUserModelNotes
	 * @since	1.1
	 */
	public function __construct($config = array())
	{
		// Set the list ordering fields.
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_id', 'a.user_id', 'user_name',
				'subject', 'a.subject',
				'catid', 'a.catid', 'category_title',
				'review_time', 'a.review_time',
			);
		}

		parent::__construct($config);
	}

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
		// Initialise variables.
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $value);

		$section = $app->getUserStateFromRequest($this->context.'.filter.section_id', 'filter_category_id');
		$this->setState('filter.category_id', $section);

		parent::populateState('a.created_time', 'DESC');
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
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Override the JModelList::getItems method.
	 *
	 * @return	array
	 * @since	1.0
	 * @throws	Exception on error.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
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
				'a.id, a.subject, a.checked_out, a.checked_out_time,' .
				'a.catid, a.created_time, a.review_time,' .
				'a.published'
			)
		);
		$query->from('#__artofuser_notes AS a');

		// Join over the section
		$query->select('c.title AS category_title, c.image AS category_image');
		$query->leftJoin('`#__categories` AS c ON c.id = a.catid');

		// Join over the users for the note user.
		$query->select('u.name AS user_name');
		$query->leftJoin('#__users AS u ON u.id = a.user_id');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->leftJoin('#__users AS uc ON uc.id = a.checked_out');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else if (stripos($search, 'uid:') === 0) {
				$query->where('a.user_id = '.(int) substr($search, 4));
			}
			else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.subject LIKE '.$search.') OR (u.name LIKE '.$search.') OR (u.username LIKE '.$search.')');
			}
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by a single or group of categories.
		$categoryId = (int) $this->getState('filter.catid');
		if ($categoryId) {
			if (is_scalar($section)) {
				$query->where('a.catid = '.$categoryId);
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

//		echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}