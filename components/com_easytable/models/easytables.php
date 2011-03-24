<?php
/**
 * @package	   EasyTable
 * @author	   Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author	   Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die ('Restricted Access');

jimport( 'joomla.application.component.model' );

/**
 * EasyTables Model
 *
 * @package	   EasyTables
 * @subpackage Models
 */
class EasyTableModelEasyTables extends JModel
{
	var $_data = null;
	
	/**
	 * Converts the sort parameter to correct SQL
	 *
	 */
	 function sortSQL($sortValue = 0)
	 {
		$theSortSQL = '';
		switch ( $sortValue )
		{
			case 1:
				$theSortSQL = 'easytablename DESC';
				break;
			case 2:
				$theSortSQL = 'created_ ASC';
				break;
			case 3:
				$theSortSQL = 'created_ DESC';
				break;
			case 4:
				$theSortSQL = 'modified_ ASC';
				break;
			case 5:
				$theSortSQL = 'modified_ DESC';
				break;
			case 0:
			default:
				$theSortSQL = 'easytablename ASC';
				break;
		}
		
		return $theSortSQL;
	 }
	
	/**
	 * Gets the tables sorted by sort value.
	 * @return data
	 */
	function &getDataSort0()
	{
		return $this->getData(0);
	}// function
	function &getDataSort1()
	{
		return $this->getData(1);
	}// function
	function &getDataSort2()
	{
		return $this->getData(2);
	}// function
	function &getDataSort3()
	{
		return $this->getData(3);
	}// function
	function &getDataSort4()
	{
		return $this->getData(4);
	}// function
	function &getDataSort5()
	{
		return $this->getData(5);
	}// function

	/**
	 * Gets the tables
	 * @return data
	 */
	function &getData($sortValue = 0)
	{
		$theSortSQL = $this->sortSQL($sortValue);
		
		if(empty($this->_data))
			{
				$query = "SELECT * FROM #__easytables WHERE `published` = '1' ORDER BY $theSortSQL";
				
				$this->_data = $this->_getList($query);
			}
		return $this->_data;
	}// function
}// class
