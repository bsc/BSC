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
 * EasyTables Model
 *
 * @package    EasyTables
 * @subpackage Models
 */
class EasyTableModelEasyTable extends JModel
{
	var $_data = null;
	var $_pagination = null;
	var $_total = null;
	var $_search = null;
	var $_search_query = null;
	
	/**
	 * Builds the search query that is used in the getData() & getTotal()
	*/
	function buildSearch()
	{
		if(!$this->_search_query)
		{
			$id = (int) JRequest::getVar('id',0);
	
			if($id)
			{
				$search = $this->getSearch($id);             // Gets the search string...
				$fields = $this->getFieldMeta($id);          // Gets the alias of all fields in the list view
				$searchFields = $this->getSearchFields($id); // Gets the alias of all text fields in table (URL & Image values are not searched)
						
				// As a default get the table data for this table
				$newSearch = "SELECT `id`, `".$fields."` FROM #__easytables_table_data_$id";  // If there is no search parameter this will return the list view fields of all records

				if($search != '')
				{
					// echo '<BR />$search == '.$search;
					// Build the WHERE part of the query using the search parameter.
					$searchFields = $this->getSearchFields($id);
					$where = array();
					$search = $this->_db->getEscaped( $search, TRUE );
					
					foreach($searchFields as $field)
					{
						$where[] = '`'.$field. "` LIKE '%{$search}%'";
					}
					$newSearch .= ' WHERE ' . implode(' OR ', $where);
					
					// echo '<BR />$newSearch: '.$newSearch;
				}
				else
				{ /* echo '<BR />$search is empty -> '.$search.' <-';*/ }
				// echo '<BR />'.$this->_search_query;
				$this->_search_query = $newSearch;
			}
			else
			{
				JError::raiseError(500,'buildSearch failed from a lack of identity - not appreciated Jan!<BR />ERROR 1337:: HANDCRAFTED URL RESPONSE 413:4');
			}
		}
		return $this->_search_query;
	}
	
	/*
	 *
	 */
	function getSearch($id='')
	{
		// echo '<BR />Entered getSearch()';
		if(!$this->_search)
		{
			global $mainframe, $option;
			$search = $mainframe->getUserStateFromRequest("$option.easytable.etsearch".$id, 'etsearch','');
			if($search == '')
			{
				// echo '<BR />$search from UserState is empty, trying request var\'s; ';
				$search = JRequest::getVar('etsearch','');
				// echo '<BR />$search from getVar is -> '.$search.' <-';
			}
			else
			{
				// echo '<BR />$search from UserState is -> '.$search.' <-';

			}
			$this->_search = JString::strtolower($search);
		}
		return $this->_search;
	}
	
	/**
	 * Get Meta data for the user table
	 */
	 function &getFieldMeta($id)
	 {
	 		// Get a database object
			$db =& JFactory::getDBO();
			if(!$db){
				JError::raiseError(500,"Couldn't get the database object while getFieldMeta() of EasyTable id: $id");
			}
			// Get the field names for this table
			
			$query = "SELECT fieldalias FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id =".$id." AND list_view = '1' ORDER BY position;";
			$db->setQuery($query);

			$fields = $db->loadResultArray();
			
			$fields = implode('`, `',$fields);
			// echo '<BR />$fields == '.$fields;
			return($fields);
	 }
	 
	 /**
	  * Get searchable fields - specifically exlude fields marked as URLs and image paths
	  */
	  function getSearchFields($id)
	  	{
	 		// Get a database object
			$db =& JFactory::getDBO();
			if(!$db){
				JError::raiseError(500,"Couldn't get the database object while getSearchFields() for EasyTable id: $id");
			}
			// Get the search fields for this table
			$query = "SELECT fieldalias FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id =".$id." AND (type = '0' || type = '3') ORDER BY position;";
			$db->setQuery($query);
			
			$fields = $db->loadResultArray();
			
			// echo '<BR />$searchFields == '.implode('`, `',$fields);
			return($fields);
		}
	
	/**
	 * Gets the record count of the table for pagination & other uses
	 * @return total
	 */
	function &getTotal()
	{
		if(empty($this->_total))
			{
				$query = $this->buildSearch();
				// echo '<BR />Query: '.$query;
				
				$this->_total = $this->_getListCount($query);
			}
		return $this->_total;
	}

	/**
	 * Creates (if necessary) and returns the pagination object
	 */
	function &getPagination ()
	{
		
		if(!$this->_pagination)
		{
			jimport('joomla.html.pagination');
			global $mainframe;

			$limit = $this->getState('limit');

			if(($limit != 0) && empty($limit))
			{
				// echo '<BR />No limit in JRequest, defaulting to site Cfg: ';
				$limit = $mainframe->getCfg('list_limit');
				// echo '<BR />$limit = '.$limit;
			}
			else
			{
				// echo '<BR />JRequest limit value = '.$limit;
			}
			$this->_pagination = new JPagination($this->getTotal(), JRequest::getVar('limitstart',0), $limit );
		}
		return $this->_pagination;
	}

	/**
	 * Gets the tables
	 * @return data
	 */
    function &getAllData()
    {
        return $this->getData(FALSE);
    }
	function &getData($et_paged=TRUE)
	{
		$pagination =& $this->getPagination();
		// echo '<BR />Pagination values for getData() limitstart = '.$pagination->limitstart.' limit = '.$pagination->limit;
		
		if(empty($this->_data))
			{
				$query = $this->buildSearch();
				
				// echo '<BR />getData() $pagination->limitstart = '.$pagination->limitstart.' $pagination->limit = '.$pagination->limit;
				
                if($et_paged)
                {
                    $this->_data = $this->_getList($query, $pagination->limitstart, $pagination->limit);
                } else {
                    $this->_data = $this->_getList($query, 0, 100000000);
                }
				//echo '<BR />getData() = '.$this->_data.'<BR />';
				//echo '<BR />'.print_r($this->_data);
			}
		return $this->_data;
	}
	
	function __construct()
	{
		parent::__construct();
		
		global $mainframe, $option;
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
  }

}// class
