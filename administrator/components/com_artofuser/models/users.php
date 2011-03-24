<?php
/**
 * @version		$Id: users.php 553 2011-01-18 23:15:07Z eddieajau $
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
class ArtofUserModelUsers extends JModelList
{
	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	public $_context = 'com_artofuser.users';

	/**
	 * Class constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.
	 *
	 * @return	ArtofUserModelUsers
	 * @since	1.1
	 */
	public function __construct($config = array())
	{
		// Set the list ordering fields.
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'username', 'a.username',
				'email', 'a.email',
				'block', 'a.block',
				'sendEmail', 'a.sendEmail',
				'registerDate', 'a.registerDate',
				'lastvisitDate', 'a.lastvisitDate',
				'activation', 'a.activation',
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

		if (!empty($items) && $this->getState('list.count_groups')) {
			// Joining the group count with the main query is a performance hog.
			// Find the information only on the result set.

			// First pass: get list of the user id's and reset the counts.
			$userIds = array();
			foreach ($items as $item)
			{
				$userIds[] = (int) $item->id;
				$item->usergroup_count = 0;
				$item->note_count = 0;
			}

			// Get the counts from the database only for the users in the list.
			$db = $this->getDbo();
			$query	= new JDatabaseQuery;

			$query->select('aro.value, COUNT(g_map.group_id) AS usergroup_count')
				->from('#__core_acl_aro AS aro')
				->innerJoin('#__core_acl_groups_aro_map AS g_map ON g_map.aro_id = aro.id')
				->where('aro.section_value = '.$db->quote('users'))
				->where('aro.value IN ('.implode(',', $userIds).')')
				->group('aro.value')
			;

			$db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$userGroups = $db->loadObjectList('value');

			$error = $db->getErrorMsg();
			if ($error) {
				$this->setError($error);

				return false;
			}

			// Count the notes attached to each user.
			$query	= new JDatabaseQuery;

			$query->select('n.user_id, COUNT(n.id) As note_count')
				->from('#__artofuser_notes AS n')
				->where('n.user_id IN ('.implode(',', $userIds).')')
				->where('n.published >= 0')
				->group('n.user_id')
			;

			$db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$userNotes = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error) {
				$this->setError($error);

				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($userGroups[$item->id])) {
					$item->usergroup_count = $userGroups[$item->id]->usergroup_count;
				}
				if (isset($userNotes[$item->id])) {
					$item->note_count = $userNotes[$item->id]->note_count;
				}
			}
		}

		return $items;
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
		$query->from('`#__users` AS a');

		// Resolve foreign keys.
		$query->select('g.name AS usergroup_name');
		$query->join('LEFT', '`#__core_acl_aro_groups` AS g ON g.id = a.gid');

		$query->select('c.title AS block_category')
			->leftJoin('`#__artofuser_blocked` AS b ON b.user_id = a.id')
			->leftJoin('`#__categories` AS c ON c.id = b.catid');

		// Apply a search filter.
		if ($search = $this->getState('filter.search')) {
			if (strpos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$search.'%');
				$query->where(
					'(a.name LIKE '.$search.
					' OR a.username LIKE '.$search.
					' OR a.email LIKE '.$search.')'
				);
			}
		}

		// Apply the blocked filter.
		$blocked = $this->getState('filter.blocked');
		if (is_numeric($blocked)) {
			$query->where('a.block = '.$blocked);
		}

		// Apply the activation filter.
		$activation = $this->getState('filter.activation');
		if (is_numeric($activation)) {
			if ($activation) {
				$query->where('a.activation = '.$db->quote(''));
			}
			else {
				$query->where('a.activation <> '.$db->quote(''));
			}
		}

		// Apply the group filter.
		$groupId = (int) $this->getState('filter.group_id');
		if ($groupId) {
			$query->innerJoin('#__core_acl_aro AS aro ON aro.value = a.id');
			$query->innerJoin('#__core_acl_groups_aro_map AS g_map2 ON g_map2.aro_id = aro.id');
			$query->where('g_map2.group_id = '.$groupId);
		}

		// Apply the range filter.
		if ($range = $this->getState('filter.range')) {

			juimport('joomla.utilities.date16');

			// Get UTC for now.
			$dNow = new JDate16;
			$dStart = clone $dNow;

			switch ($range)
			{
				case 'past_week':
					$dStart->modify('-7 day');
					break;
				case 'past_1month':
					$dStart->modify('-1 month');
					break;
				case 'past_3month':
					$dStart->modify('-3 month');
					break;
				case 'past_6month':
					$dStart->modify('-6 month');
					break;
				case 'post_year':
				case 'past_year':
					$dStart->modify('-1 year');
					break;

				case 'today':
					// Ranges that need to align with local 'days' need special treatment.
					$app	= JFactory::getApplication();
					$offset	= $app->getCfg('offset');

					// Reset the start time to be the beginning of today, local time.
					$dStart	= new JDate16('now', $offset);
					$dStart->setTime(0,0,0);

					// Now change the timezone back to UTC.
					$dStart->setOffset(0);
					break;
			}

			if ($range == 'post_year') {
				$query->where(
					'a.registerDate < '.$db->quote($dStart->format('Y-m-d H:i:s'))
				);
			}
			else {
				$query->where(
					'a.registerDate >= '.$db->quote($dStart->format('Y-m-d H:i:s')).
					' AND a.registerDate <='.$db->quote($dNow->format('Y-m-d H:i:s'))
				);
			}
		}

		// Add the list ordering clause.
		$query->order($this->_db->getEscaped($this->getState('list.ordering', 'a.name')).' '.$this->_db->getEscaped($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',(string) $query);
		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.group_id');

		$id	.= ':'.$this->getState('list.start');
		$id	.= ':'.$this->getState('list.limit');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');

		return md5($id);
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

		$groupId = (int) $app->getUserStateFromRequest($this->_context.'.group_id', 'filter_group_id');
		$this->setState('filter.group_id', $groupId);

		$blocked = $app->getUserStateFromRequest($this->_context.'.blocked', 'filter_blocked');
		$this->setState('filter.blocked', $blocked);

		$activation = $app->getUserStateFromRequest($this->_context.'.activation', 'filter_activation');
		$this->setState('filter.activation', $activation);

		$range = $app->getUserStateFromRequest($this->_context.'.range', 'filter_range');
		$this->setState('filter.range', $range);

		$this->setState('list.count_groups', true);

		parent::populateState('a.name', 'ASC');
	}
}