<?php
/**
 * @version		$Id: group.php 537 2011-01-15 02:22:57Z eddieajau $
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

juimport('joomla.application.component.model16');
juimport('joomla.database.databasequery');

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 */
class ArtofUserModelGroup extends JModel16
{
	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param	type 	The table type to instantiate
	 * @param	string 	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Group', $prefix = 'AclTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Auto-populate the model state.
	 *
	 * @return	void
	 */
	protected function populateState()
	{
		$app = &JFactory::getApplication('administrator');

		// Load the User state.
		if (!($pk = (int) $app->getUserState('com_artofuser.edit.group.id'))) {
			$pk = JRequest::getInt('group_id');
		}

		$this->setState('group.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_artofuser');
		$this->setState('params', $params);
	}

	/**
	 * Method to get a row.
	 *
	 * @param	integer	An optional id of the object to get, otherwise the id from the model state is used.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int)$this->getState('group.id');

		// Get a level row instance.
		$table = &$this->getTable();

		// Attempt to load the row.
		$table->load($pk);

		// Check for a table object error.
		if ($error = $table->getError()) {
			$this->setError($error);
			$false = false;
			return $false;
		}

		// Convert the JTable to a clean JObject.
		$result = JArrayHelper::toObject($table->getProperties(1), 'JObject');

		return $result;
	}

	/**
	 * Method to get the form object.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function &getForm()
	{
		// Initialize variables.
		$app	= JFactory::getApplication();
		$false	= false;

		// Get the form.
		juimport('jxtended.form.form');
		JForm::addFormPath(JPATH_COMPONENT.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		$form = JForm::getInstance('group', 'jform', true, array('array' => 'jform'));

		// Check for an error.
		if (JError::isError($form)) {
			$this->setError($form->getMessage());
			return $false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('artofuser.edit.group.data', array());

		// Bind the form data if present.
		if (!empty($data)) {
			$form->bind($data);
		}

		return $form;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 */
	public function save($data)
	{
		$acl		= &JFactory::getACL();

		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('group.id');
		$parentId	= JArrayHelper::getValue($data, 'parent_id');
		$name		= JArrayHelper::getValue($data, 'name');
		$value		= JArrayHelper::getValue($data, 'value');

		if ($pk) {
			// Existing
			$result = $acl->edit_group($pk, $value, $name, $parentId, 'aro');
		}
		else {
			// New
			$result = $acl->add_group($value, $name, $parentId, 'aro');
			$pk		= $result;
		}

		if (!$result) {
			//$result = JError::raiseNotice(500, 'Failed to save group');
			$result = JError::raiseWarning(500, array_pop($acl->_debugLog));
			return false;
		}
		else {
			$this->setState('group.id', $pk);
		}

		return true;
	}

	/**
	 * Method to delete groups.
	 *
	 * @param	array	An array of group ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete($pks = array())
	{
		$acl	= JFactory::getACL();
		$db		= $this->getDbo();
		$tables	= $db->getTableList();
		$native	= in_array($db->getPrefix().'core_acl_aro_groups_map', $tables);

		foreach ((array) $pks as $pk)
		{
			if ($native) {
				// Control is probably installed so use the native API method.
				$result = $acl->del_group($pk, true, 'aro');
			}
			else {
				// Use this version's of del_group to avoid the SQL errors caused by the lack of the core_acl_aro_groups_map table.
				$result = $this->del_group($pk, true, 'aro');
			}

			if ($result == false) {
				JError::raiseWarning(500, array_pop($acl->_debugLog));
				break;
			}
		}

		return $result;
	}

	/**
	 * Override for gacl_api::del_group()
	 *
	 * Deletes a given group.  We need to override this because some of the GACL tables are missing in a stock Joomla.
	 *
	 * @return bool Returns TRUE if successful, FALSE otherwise.
	 *
	 * @param int Group ID #
	 * @param bool If TRUE, child groups of this group will be reparented to the current group's parent.
	 * @param string Group Type, either 'ARO' or 'AXO'
	 */
	protected function del_group($group_id, $reparent_children=TRUE)
	{
		$acl = JFactory::getAcl();

		$group_type = 'aro';
		$table = $acl->_db_table_prefix .'aro_groups';
		//$groups_map_table = $acl->_db_table_prefix .'aro_groups_map';
		$groups_object_map_table = $acl->_db_table_prefix .'groups_aro_map';

		$acl->debug_text("ArtofUserModelGroup::del_group(): ID: $group_id Reparent Children: $reparent_children Group Type: ARO");

		if (empty($group_id) ) {
			$acl->debug_text("del_group(): Group ID ($group_id) is empty, this is required");
			return false;
		}

		// Get details of this group
		$query = 'SELECT id, parent_id, name, lft, rgt FROM '. $table .' WHERE id='. (int) $group_id;
		$group_details = $acl->db->GetRow($query);

		if (!is_array($group_details)) {
			$acl->debug_db('del_group');
			return false;
		}

		$parent_id = $group_details[1];

		$left = $group_details[3];
		$right = $group_details[4];

		$acl->db->BeginTrans();

		// grab list of all children
		$children_ids = $acl->get_group_children($group_id, $group_type, 'RECURSE');

		// prevent deletion of root group & reparent of children if it has more than one immediate child
		if ($parent_id == 0) {
			$query = 'SELECT count(*) FROM '. $table .' WHERE parent_id='. (int) $group_id;
			$child_count = $acl->db->GetOne($query);

			if (($child_count > 1) AND $reparent_children) {
				$acl->debug_text ('del_group (): You cannot delete the root group and reparent children, this would create multiple root groups.');
				$acl->db->RollbackTrans();
				return FALSE;
			}
		}

		$success = FALSE;

		/*
		 * Handle children here.
		 */
		switch (TRUE) {
			// there are no child groups, just delete group
			case !is_array($children_ids):
			case count($children_ids) == 0:
				// remove group object maps
				$query = 'DELETE FROM '. $groups_object_map_table .' WHERE group_id='. (int) $group_id;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// remove group
				$query = 'DELETE FROM '. $table .' WHERE id='. (int) $group_id;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// move all groups right of deleted group left by width of deleted group
				$query = 'UPDATE '. $table .' SET lft=lft-'. (int)($right-$left+1) .' WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$query = 'UPDATE '. $table .' SET rgt=rgt-'. (int)($right-$left+1) .' WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$success = TRUE;
				break;
			case $reparent_children == TRUE:
				// remove group object maps
				$query = 'DELETE FROM '. $groups_object_map_table .' WHERE group_id='. (int) $group_id;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// remove group
				$query = 'DELETE FROM '. $table .' WHERE id='. (int) $group_id;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// set parent of immediate children to parent group
				$query = 'UPDATE '. $table .' SET parent_id='. (int) $parent_id .' WHERE parent_id='. (int) $group_id;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// move all children left by 1
				$query = 'UPDATE '. $table .' SET lft=lft-1, rgt=rgt-1 WHERE lft>'. (int) $left .' AND rgt<'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// move all groups right of deleted group left by 2
				$query = 'UPDATE '. $table .' SET lft=lft-2 WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$query = 'UPDATE '. $table .' SET rgt=rgt-2 WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$success = TRUE;
				break;
			default:
				// make list of group and all children
				$group_ids = $children_ids;
				$group_ids[] = (int) $group_id;

				// remove group object maps
				$query = 'DELETE FROM '. $groups_object_map_table .' WHERE group_id IN ('. implode (',', $group_ids) .')';
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// remove groups
				$query = 'DELETE FROM '. $table .' WHERE id IN ('. implode (',', $group_ids) .')';
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				// move all groups right of deleted group left by width of deleted group
				$query = 'UPDATE '. $table .' SET lft=lft-'. ($right - $left + 1) .' WHERE lft>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$query = 'UPDATE '. $table .' SET rgt=rgt-'. ($right - $left + 1) .' WHERE rgt>'. (int) $right;
				$rs = $acl->db->Execute($query);

				if (!is_object($rs)) {
					break;
				}

				$success = TRUE;
		}

		// if the delete failed, rollback the trans and return false
		if (!$success) {

			$acl->debug_db('del_group');
			$acl->db->RollBackTrans();
			return false;
		}

		$acl->debug_text("del_group(): deleted group ID: $group_id");
		$acl->db->CommitTrans();

		if ($acl->_caching == TRUE AND $acl->_force_cache_expire == TRUE) {
			//Expire all cache.
			$acl->Cache_Lite->clean('default');
		}

		return true;
	}

	/**
	 * Method to validate the form data.
	 *
	 * @param	array	The form data.
	 * @return	mixed	Array of filtered data if valid, false otherwise.
	 * @since	1.0
	 */
	public function validate($data)
	{
		// Get the form.
		$form = &$this->getForm();

		// Check for an error.
		if ($form === false) {
			return false;
		}

		// Filter and validate the form data.
		$data	= $form->filter($data);
		$return	= $form->validate($data);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message)
			{
				$this->setError($message);
			}

			return false;
		}

		return $data;
	}
}