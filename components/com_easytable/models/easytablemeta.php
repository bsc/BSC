<?php
/**
 * @package    EasyTable
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die ('Restricted Access');

jimport( 'joomla.application.component.model' );

/**
 * EasyTableMeta Model
 *
 * @package    EasyTables
 * @subpackage Models
 */
class EasyTableModelEasyTableMeta extends JModel
{
	var $_data = null;
	
	/**
	 * Gets the tables
	 * @return data
	 */
	function &getData($id)
	{
		if(empty($this->_data))
			{
				$query = "SELECT * FROM #__easytables_table_meta WHERE id = '$id' ORDER BY easytablename ASC";
				
				$this->_data = $this->_getList($query);
			}
		return $this->_data;
	}// function
}// class
