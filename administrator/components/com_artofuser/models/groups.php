<?php
/**
 * @version		$Id: groups.php 546 2011-01-15 05:00:00Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

juimport('joomla.application.component.modellist');
juimport('joomla.database.databasequery');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserModelGroups extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_artofuser.groups';

	/**
	 * Class constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.
	 *
	 * @return	ArtofUserModelGroups
	 * @since	1.1
	 */
	public function __construct($config = array())
	{
		// Set the list ordering fields.
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'parent_id', 'a.parent_id',
				'title', 'a.title',
				'lft', 'a.lft',
				'rgt', 'a.rgt',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Override JModelList::getItems to join other information.
	 *
	 * @return	array
	 * @since	1.0.1
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if (!empty($items) && $this->getState('list.count_users')) {
			// Joining the group count with the main query is a performance hog.
			// Find the information only on the result set.

			// First pass: get list of the group id's and reset the counts.
			$groupIds = array();
			foreach ($items as $item)
			{
				$groupIds[] = (int) $item->id;
				$item->user_count = 0;
			}

			// Get the counts from the database only for the users in the list.
			$db = $this->getDbo();
			$query	= new JDatabaseQuery;

			$query->select('map.group_id, COUNT(DISTINCT map.aro_id) AS user_count');
			$query->from('#__core_acl_groups_aro_map AS map');
			$query->where('map.group_id IN ('.implode(',', $groupIds).')');
			$query->group('map.group_id');

			$db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$users = $db->loadObjectList('group_id');

			$error = $db->getErrorMsg();
			if ($error) {
				$this->setError($error);
				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($users[$item->id])) {
					$item->user_count = $users[$item->id]->user_count;
				}
			}
		}

		return $items;
	}

	/**
	 * Overridden method to lazy load data from the request/session as necessary
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication();
		if ($layout	= JRequest::getVar('layout')) {
			$this->_context .= '.'.$layout;
		}

		$search = $app->getUserStateFromRequest($this->_context.'.search', 'filter_search');
		$this->setState('filter.search', $search);

		$this->setState('filter.parent_id', 28);

		$this->setState('filter.tree', true);

		$this->setState('list.count_users', true);

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->getUserStateFromRequest($this->_context.'.limitstart', 'limitstart', 0);
		$this->setState('list.start', $limitstart);

		$orderCol = $app->getUserStateFromRequest($this->_context.'.ordercol', 'filter_order', 'a.lft');
		$this->setState('list.ordering', $orderCol);

		$orderDirn = $app->getUserStateFromRequest($this->_context.'.orderdirn', 'filter_order_Dir', 'asc');
		$this->setState('list.direction', $orderDirn);
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
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.parent_id');
		$id	.= ':'.$this->getState('filter.tree');

		$id	.= ':'.$this->getState('list.start');
		$id	.= ':'.$this->getState('list.limit');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');

		return md5($id);
	}

	/**
	 * Gets a list of categories objects
	 *
	 * Filters may be fields|published|order by|searchName|where
	 * @param boolean True if foreign keys are to be resolved
	 */
	protected function getListQuery()
	{
		$db	= $this->getDBO();
		$query = new JDatabaseQuery;

		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__core_acl_aro_groups AS a');

		// Resolve foreign keys.
		if ($this->getState('filter.tree')) {
			$query->select('COUNT(DISTINCT c2.id) AS level');
			$query->join('LEFT OUTER', '#__core_acl_aro_groups AS c2 ON a.lft > c2.lft AND a.rgt < c2.rgt');
			$query->group('a.id');
		}

		if ($parentId = $this->getState('filter.parent_id')) {
			$query->join('LEFT', '#__core_acl_aro_groups AS p ON p.id = '.(int) $parentId);
			$query->where('a.lft > p.lft AND a.rgt < p.rgt');
		}

		// Apply a search filter.
		if ($search = $this->getState('filter.search')) {
			if (strpos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$search.'%');
				$query->where('a.name LIKE '.$search);
			}
		}

		// Add the list ordering clause.
		$query->order($this->_db->getEscaped($this->getState('list.ordering', 'a.lft')).' '.$this->_db->getEscaped($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',(string) $query));
		return $query;
	}
}