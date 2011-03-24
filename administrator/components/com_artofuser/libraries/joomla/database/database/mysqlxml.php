<?php
/**
 * @version		$Id: mysqlxml.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 * @author		Andrew Eddie <andrew.eddie@newlifeinit.com>
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @since		1.0
 */
class JDatabaseMySQLXML
{
	/**
	 * @var		array	Cache for column data.
	 * @since	1.0
	 */
	protected static $columnCache = array();

	/**
	 * @var		array	Cache for column data.
	 * @since	1.0
	 */
	protected static $keyCache = array();

	/**
	 * @var		array	A log of the queries executed.
	 * @since	1.0
	 */
	protected static $log = array();

	/**
	 * Get the query log.
	 *
	 * @return	array
	 * @since	1.0
	 */
	public static function addLog($query)
	{
		self::$log[] = (string) $query;
	}

	/**
	 * Exports a table in XML format.
	 *
	 * @param	mixed	$tabls		The names of the tables to export, or the name of a single table.
	 * @param	boolean	$structure	Optionally export the structure (default true).
	 * @param	boolean	$data		Optionally export the data (default false).
	 *
	 * @return	string	The dump of the table in XML format.
	 * @since	1.0
	 * @throws	Exception
	 * @link	http://dev.mysql.com/tech-resources/articles/xml-in-mysql5.1-6.0.html
	 */
	public static function export($tables, $structure = true, $data = false)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$prefix	= $db->getPrefix();

		if (is_string($tables)) {
			$tables = array($tables);
		}

		// Get some database information.
		$db->setQuery(
		   'SHOW VARIABLES WHERE Variable_name IN (\'character_set_database\', \'storage_engine\')'
		);
		$settings = (object) $db->loadObjectList('Variable_name');

			// Check for a db error.
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		$buffer = array();

		$buffer[] = '<?xml version="1.0"?>';
		$buffer[] = '<mysqldump xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$buffer[] = ' <database name="">';

		foreach ($tables as $table)
		{
			// Replace the magic prefix if found.
			$table = preg_replace('#^$prefix#', '#__', $table);

			// Get the details columns information.
			$fields	= self::getColumns($table);
			$keys	= self::getKeys($table);

			$buffer[] = '  <table_structure name="'.$table .'">';

			foreach ($fields as $field) {
				$buffer[] = '   <field Field="'.$field->Field.'"'.
					' Type="'.$field->Type.'"'.
					' Null="'.$field->Null.'"'.
					' Key="'.$field->Key.'"'.
					(isset($field->Default) ? ' Default="'.$field->Default.'"' : '').
					' Extra="'.$field->Extra.'"'.
					// Not sure if the following comply with the spec.
					//($field->Collation ? ' Collation="'.$field->Collation.'"' : '').
					//' Comment="'.$field->Comment.'"'.
					' />';
			}

			foreach ($keys as $key) {
				$buffer[] = '   <key Table="'.$table.'"'.
					' Non_unique="'.$key->Non_unique.'"'.
					' Key_name="'.$key->Key_name.'"'.
					' Seq_in_index="'.$key->Seq_in_index.'"'.
					' Column_name="'.$key->Column_name.'"'.
					' Collation="'.$key->Collation.'"'.
					' Null="'.$key->Null.'"'.
					' Index_type="'.$key->Index_type.'"'.
					' Comment="'.htmlspecialchars($key->Comment).'"'.
					' />';

			}

//			$buffer[] = '   <options Name="'.$table.'"' .
//					' Engine="'.$settings->.'"' .
//					' Collation=""' .
//					'>';

			$buffer[] = '  </table_structure>';
		}

		$buffer[] = ' </database>';
		$buffer[] = '</mysqldump>';

		return implode("\n", $buffer);
	}

	/**
	 * Get the SQL syntax to add a column.
	 *
	 * @param	string				$table	The table name.
	 * @param	SimpleXMLElement	$field	The XML field definition.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getAddColumnSQL($table, SimpleXMLElement $field)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' ADD COLUMN '.self::getColumnSQL($field);

		return $sql;
	}

	/**
	 * Get the SQL syntax to add a column.
	 *
	 * @param	string	$table	The table name.
	 * @param	array	$keys	An array of the fields pertaining to this key.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getAddKeySQL($table, $keys)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' ADD '.self::getKeySQL($keys);

		return $sql;
	}

	/**
	 * Get the syntax to alter a column.
	 *
	 * @param	string
	 * @param	SimpleXMLElement
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getChangeColumnSQL($table, SimpleXMLElement $field)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' CHANGE COLUMN '.$db->nameQuote((string) $field['Field']).
			' '.self::getColumnSQL($field);

		return $sql;
	}

	/**
	 * Get alters for table if there is a difference.
	 *
	 * @param	SimpleXMLElement	$structure
	 *
	 * @return	array
	 * @since	1.0
	 */
	public static function getAlterTableSQL(SimpleXMLElement $structure)
	{
		// Initialise variables.
		$table		= self::getRealTableName($structure['name']);
		$oldFields	= self::getColumns($table);
		$oldKeys	= self::getKeys($table);
		$alters		= array();

		// Get the fields and keys from the XML that we are aiming for.
		$newFields 	= $structure->xpath('field');
		$newKeys	= $structure->xpath('key');

		// Loop through each field in the new structure.
		foreach ($newFields as $field)
		{
			$fName = (string) $field['Field'];

			if (isset($oldFields[$fName])) {
				// The field exists, check it's the same.
				$column = $oldFields[$fName];

				// Test whether there is a change.
				$change = ((string) $field['Type'] != $column->Type)
					|| ((string) $field['Null'] != $column->Null)
					|| ((string) $field['Default'] != $column->Default)
					|| ((string) $field['Extra'] != $column->Extra)
					;

				if ($change) {
					$alters[] = self::getChangeColumnSQL($table, $field);
				}

				// Unset this field so that what we have left are fields that need to be removed.
				unset($oldFields[$fName]);
			}
			else {
				// The field is new.
				$alters[] = self::getAddColumnSQL($table, $field);
			}
		}

		// Any columns left are orphans
		foreach ($oldFields as $name => $column)
		{
			// Delete the column.
			$alters[] = self::getDropColumnSQL($table, $name);
		}

		// Get the lookups for the old and new keys.
		$oldLookup	= self::getKeyLookup($oldKeys);
		$newLookup	= self::getKeyLookup($newKeys);

		// Loop through each key in the new structure.
		foreach ($newLookup as $name => $keys)
		{
			// Check if there are keys on this field in the existing table.
			if (isset($oldLookup[$name])) {
				$same = true;
				$newCount	= count($newLookup[$name]);
				$oldCount	= count($oldLookup[$name]);

				// There is a key on this field in the old and new tables. Are they the same?
				if ($newCount == $oldCount) {
					// Need to loop through each key and do a fine grained check.
					for ($i = 0; $i < $newCount; $i++)
					{
						$same = (
							((string) $newLookup[$name][$i]['Non_unique'] == $oldLookup[$name][$i]->Non_unique)
							&& ((string) $newLookup[$name][$i]['Column_name'] == $oldLookup[$name][$i]->Column_name)
							&& ((string) $newLookup[$name][$i]['Seq_in_index'] == $oldLookup[$name][$i]->Seq_in_index)
							&& ((string) $newLookup[$name][$i]['Collation'] == $oldLookup[$name][$i]->Collation)
							&& ((string) $newLookup[$name][$i]['Index_type'] == $oldLookup[$name][$i]->Index_type)
							);

						// Debug.
//						echo '<pre>';
//						echo '<br />Non_unique:   '.
//							((string) $newLookup[$name][$i]['Non_unique'] == $oldLookup[$name][$i]->Non_unique ? 'Pass' : 'Fail').' '.
//							(string) $newLookup[$name][$i]['Non_unique'].' vs '.$oldLookup[$name][$i]->Non_unique;
//						echo '<br />Column_name:  '.
//							((string) $newLookup[$name][$i]['Column_name'] == $oldLookup[$name][$i]->Column_name ? 'Pass' : 'Fail').' '.
//							(string) $newLookup[$name][$i]['Column_name'].' vs '.$oldLookup[$name][$i]->Column_name;
//						echo '<br />Seq_in_index: '.
//							((string) $newLookup[$name][$i]['Seq_in_index'] == $oldLookup[$name][$i]->Seq_in_index ? 'Pass' : 'Fail').' '.
//							(string) $newLookup[$name][$i]['Seq_in_index'].' vs '.$oldLookup[$name][$i]->Seq_in_index;
//						echo '<br />Collation:    '.
//							((string) $newLookup[$name][$i]['Collation'] == $oldLookup[$name][$i]->Collation ? 'Pass' : 'Fail').' '.
//							(string) $newLookup[$name][$i]['Collation'].' vs '.$oldLookup[$name][$i]->Collation;
//						echo '<br />Index_type:   '.
//							((string) $newLookup[$name][$i]['Index_type'] == $oldLookup[$name][$i]->Index_type ? 'Pass' : 'Fail').' '.
//							(string) $newLookup[$name][$i]['Index_type'].' vs '.$oldLookup[$name][$i]->Index_type;
//						echo '<br />Same = '.($same ? 'true' : 'false');
//						echo '</pre>';

						if (!$same) {
							// Break out of the loop. No need to check further.
							break;
						}
					}
				}
				else {
					// Count is different, just drop and add.
					$same = false;
				}

				if (!$same) {
					$alters[] = self::getDropKeySQL($table, $name);
					$alters[] = self::getAddKeySQL($table, $keys);
				}
			}
			else {
				// This is a new key.
				$alters[] = self::getAddKeySQL($table, $keys);
			}
		}

		return $alters;
	}

	/**
	 * Get the details list of columns for a table.
	 *
	 * @param	string	$table	The name of the table.
	 *
	 * @return	array	An arry of the column specification for the table.
	 * @since	1.0
	 * @throws	Exception
	 */
	public static function getColumns($table)
	{
		if (empty(self::$columnCache[$table])) {
			// Initialise variables.
			// TODO Incorporate into parent class and use $this.
			$db		= JFactory::getDbo();

			// Get the details columns information.
			$db->setQuery(
				'SHOW FULL COLUMNS FROM '.$db->nameQuote($table)
			);
			self::$columnCache[$table] = $db->loadObjectList('Field');

			// Check for a db error.
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
		}

		return self::$columnCache[$table];
	}

	/**
	 * Get the SQL syntax for a single column.
	 *
	 * @param	SimpleXMLElement	$field	The XML field definition.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getColumnSQL(SimpleXMLElement $field)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$blobs	= array(
			'text',
			'smalltext',
			'mediumtext',
			'largetext'
		);

		$fName		= (string) $field['Field'];
		$fType		= (string) $field['Type'];
		$fNull		= (string) $field['Null'];
		$fKey		= (string) $field['Key'];
		$fDefault	= isset($field['Default']) ? (string) $field['Default'] : null;
		$fExtra		= (string) $field['Extra'];

		$sql = $db->nameQuote($fName).' '.$fType;

		if ($fNull == 'NO') {
			if (in_array($fType, $blobs) || $fDefault === null) {
				$sql .= ' NOT NULL';
			}
			else {
				// TODO Don't quote numeric values.
				$sql .= ' NOT NULL DEFAULT '.$db->quote($fDefault);
			}
		}
		else {
			if ($fDefault === null) {
				$sql .= ' DEFAULT NULL';
			}
			else {
				// TODO Don't quote numeric values.
				$sql .= ' DEFAULT '.$db->quote($fDefault);
			}
		}

		if ($fExtra) {
			$sql .= ' '.strtoupper($fExtra);
		}

		return $sql;
	}

	/**
	 * Get the query log.
	 *
	 * @return	array
	 * @since	1.0
	 */
	public static function getLog()
	{
		return self::$log;
	}

	/**
	 * Get the SQL syntax to drop a column.
	 *
	 * @param	string	$table	The table name.
	 * @param	string	$name	The name of the field to drop.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getDropColumnSQL($table, $name)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' DROP COLUMN '.$db->nameQuote($name);

		return $sql;
	}

	/**
	 * Get the SQL syntax to drop a key.
	 *
	 * @param	string	$table	The table name.
	 * @param	string	$field	The name of the key to drop.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getDropKeySQL($table, $name)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' DROP KEY '.$db->nameQuote($name);

		return $sql;
	}

	/**
	 * Get the SQL syntax to drop a key.
	 *
	 * @param	string	$table	The table name.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getDropPrimaryKeySQL($table)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		$sql = 'ALTER TABLE '.$db->nameQuote($table).
			' DROP PRIMARY KEY';

		return $sql;
	}

	/**
	 * Get the generic name of the table, converting the database prefix to the wildcard string.
	 *
	 * @param	string	$table	The name of the table.
	 *
	 * @return	string	The real name of the table.
	 * @since	1.0
	 */
	protected static function getGenericTableName($table)
	{
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$prefix	= $db->getPrefix();

		// Replace the magic prefix if found.
		$table = preg_replace('#^$prefix#', '#__', $table);

		return $table;
	}

	/**
	 * Get the details list of keys for a table.
	 *
	 * @param	string	$table	The name of the table.
	 *
	 * @return	array	An arry of the column specification for the table.
	 * @since	1.0
	 * @throws	Exception
	 */
	public static function getKeys($table)
	{
		if (empty(self::$keyCache[$table])) {
			// Initialise variables.
			// TODO Incorporate into parent class and use $this.
			$db = JFactory::getDbo();

			// Get the details columns information.
			$db->setQuery(
				'SHOW KEYS FROM '.$db->nameQuote($table)
			);
			self::$keyCache[$table] = $db->loadObjectList();

			// Check for a db error.
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
		}

		return self::$keyCache[$table];
	}

	/**
	 * Get the details list of keys for a table.
	 *
	 * @param	array	$keys	An array of objects that comprise the keys for the table.
	 *
	 * @return	array	The lookup array. array({key name} => array(object, ...))
	 * @since	1.0
	 * @throws	Exception
	 */
	public static function getKeyLookup($keys)
	{
		// First pass, create a lookup of the keys.
		$lookup	= array();
		foreach ($keys as $key)
		{
			if ($key instanceof SimpleXMLElement) {
				$kName = (string) $key['Key_name'];
			}
			else {
				$kName = $key->Key_name;
			}
			if (empty($lookup[$kName])) {
				$lookup[$kName] = array();
			}
			$lookup[$kName][] = $key;
		}

		return $lookup;
	}

	/**
	 * Get the SQL syntax for a key.
	 *
	 * @param	array	$columns	An array of SimpleXMLElement objects comprising the key.
	 *
	 * @return	string
	 * @since	1.0
	 */
	protected static function getKeySQL($columns)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();

		// TODO Error checking on array and element types.

		$kNonUnique	= (string) $columns[0]['Non_unique'];
		$kName		= (string) $columns[0]['Key_name'];
		$kColumn	= (string) $columns[0]['Column_name'];
		$kCollation	= (string) $columns[0]['Collation'];
		$kNull		= (string) $columns[0]['Null'];
		$kType		= (string) $columns[0]['Index_type'];
		$kComment	= (string) $columns[0]['Comment'];

		$prefix = '';
		if ($kName == 'PRIMARY') {
			$prefix = 'PRIMARY ';
		}
		else if ($kNonUnique == 0) {
			$prefix = 'UNIQUE ';
		}

		$nColumns = count($columns);
		$kColumns = array();

		if ($nColumns == 1) {
			$kColumns[] = $db->nameQuote($kColumn);
		}
		else {
			foreach ($columns as $column) {
				$kColumns[] = (string) $column['Column_name'];
			}
		}

		$sql = $prefix.'KEY '.($kName != 'PRIMARY' ? $db->nameQuote($kName) : '').' ('.implode(',', $kColumns).')';

		return $sql;
	}

	/**
	 * Get the real name of the table, converting the prefix wildcard string if present.
	 *
	 * @param	string	$table	The name of the table.
	 *
	 * @return	string	The real name of the table.
	 * @since	1.0
	 */
	protected static function getRealTableName($table)
	{
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$prefix	= $db->getPrefix();

		// Replace the magic prefix if found.
		$table = preg_replace('|^#__|', $prefix, $table);

		return $table;
	}

	/**
	 * Imports an XML specification into the database.
	 *
	 * @since	1.0
	 * @throws	Exception
	 */
	public static function import($data)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$prefix	= $db->getPrefix();
		$tables	= $db->getTableList();
		$xml	= new SimpleXMLElement($data);
		$result	= true;

		// Get all the table definitions.
		$xmlTables	= $xml->xpath('database/table_structure');

		foreach ($xmlTables as $table)
		{
			// Convert the magic prefix into the real table name.
			$tableName = (string) $table['name'];
			$tableName = preg_replace('|^#__|', $prefix, $tableName);

			if (in_array($tableName, $tables)) {
				// The table already exists. Now check if there is any difference.
				if ($queries = self::getAlterTableSQL($xml->database->table_structure)) {
					// Run the queries to upgrade the data structure.
					foreach ($queries as $query)
					{
						$db->setQuery((string) $query);
						if (!$db->query()) {
							self::addLog('Fail: '.$db->getQuery());
							throw new Exception($db->getErrorMsg());
						}
						else {
							self::addLog('Pass: '.$db->getQuery());
						}
					}

				}
			}
			else {
				// This is a new table.
				$sql = self::xmlToCreate($table);

				$db->setQuery((string) $sql);
				if (!$db->query()) {
					self::addLog('Fail: '.$db->getQuery());
					throw new Exception($db->getErrorMsg());
				}
				else {
					self::addLog('Pass: '.$db->getQuery());
				}
			}
		}
	}

	/**
	 * Turns an XML elements into a create statement.
	 *
	 * @param	SimpleXMLElement	$xml
	 *
	 * @return	string	The create statement for the table.
	 * @since	1.0
	 * @throws	Exception
	 */
	public static function xmlToCreate(SimpleXMLElement $xml)
	{
		// Initialise variables.
		// TODO Incorporate into parent class and use $this.
		$db		= JFactory::getDbo();
		$table	= $xml['name'];
		$sql	= 'CREATE TABLE IF NOT EXISTS '.$db->nameQuote($table).'('."\n";
		$parts	= array();

		$fields = $xml->xpath('field');
		foreach ($fields as $field)
		{
			$parts[] = self::getColumnSQL($field);
		}

		$keys	= $xml->xpath('key');

		// First pass, create a lookup of the keys.
		$lookup	= self::getKeyLookup($keys);

		// Second pass, through the lookup array fo the key names.
		foreach ($lookup as $kColumn => $columns)
		{
			$parts[] = self::getKeySQL($columns);
		}

		$sql .= implode(",\n", $parts);

		// TODO Do the engine, charset and collation properly.
		$sql .= "\n".') ENGINE=MyISAM DEFAULT CHARSET=utf8';

		// Debug
//		echo '<pre>'.$sql.'</pre>';

		return $sql;
	}
}

