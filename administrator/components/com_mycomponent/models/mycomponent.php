<?php 

/**
 * Joomla! 1.5 component mycomponent
 * Code generated by : Danny's Joomla! 1.5 MVC Component Code Generator
 * http://www.joomlafreak.be
 * date generated:  
 * @version 0.8
 * @author Danny Buytaert 
 * @package com_mycomponent
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.application.component.model');
class MycomponentModelMycomponent extends JModel
  {
  /**
  * Mycomponent array
  *
  * @var array
  */
  var $_data = null;
 /**
  * Mycomponent total
  *
  * @var integer
  */
  var $_total = null;
 /**
  * Pagination object
  *
  * @var object
  */
  var $_pagination = null;
 /**
  * Constructor
  */
  function __construct()
  {
  parent::__construct();
 global $mainframe, $option;
 // Get the pagination request variables
  $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
  $limitstart = $mainframe->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
 $this->setState('limit', $limit);
  $this->setState('limitstart', $limitstart);
  }
 /**
  * Method to get mycomponent item data
  *
  * @access public
  * @return array
  */
  function getData()
  {
  // Lets load the content if it doesn't already exist
  if (empty($this->_data))
  {
  $query = $this->_buildQuery();
  $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
  }
 return $this->_data;
  }
 /**
  * Method to get the total number of mycomponent items
  *
  * @access public
  * @return integer
  */
  function getTotal()
  {
  // Lets load the content if it doesn't already exist
  if (empty($this->_total))
  {
  $query = $this->_buildQuery();
  $this->_total = $this->_getListCount($query);
  }
 return $this->_total;
  }
 /**
  * Method to get a pagination object for mycomponent 
  *
  * @access public
  * @return integer
  */
  function getPagination()
  {
  // Lets load the content if it doesn't already exist
  if (empty($this->_pagination))
  {
  jimport('joomla.html.pagination');
  $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
  }
 return $this->_pagination;
  }
 function _buildQuery()
  {
  // Get the WHERE and ORDER BY clauses for the query
  $where = $this->_buildContentWhere();
  $orderby = $this->_buildContentOrderBy();
 $query = ' SELECT a.*, cc.title AS category, u.name AS editor, v.name AS author '
  . ' FROM #__mycomponent AS a '
  . ' LEFT JOIN #__categories AS cc ON cc.id = a.catid '
  . ' LEFT JOIN #__users AS u ON u.id = a.modified_by '
  . ' LEFT JOIN #__users AS v ON v.id = a.created_by '
  . $where
  . $orderby
  ;
 return $query;
  }
 function _buildContentOrderBy()
  {
  global $mainframe, $option;
 $filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.ordering', 'cmd' );
  $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
 if ($filter_order == 'a.ordering'){
  $orderby = ' ORDER BY category, a.ordering '.$filter_order_Dir;
  } else {
  $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , category, a.ordering ';
  }
 return $orderby;
  }
 function _buildContentWhere()
  {
  global $mainframe, $option;
 $filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
  $filter_catid = $mainframe->getUserStateFromRequest( $option.'filter_catid', 'filter_catid', 0, 'int' );
  $filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.ordering', 'cmd' );
  $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
  $search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
  $search = JString::strtolower( $search );
 $where = array();
 if ($filter_catid > 0) {
  $where[] = 'a.catid = '.(int) $filter_catid;
  }
  if ($search) {
  $where[] = 'LOWER(a.title) LIKE '.$this->_db->Quote('%'.$search.'%');
  }
  if ( $filter_state ) {
  if ( $filter_state == 'P' ) {
  $where[] = 'a.published = 1';
  } else if ($filter_state == 'U' ) {
  $where[] = 'a.published = 0';
  }
  }
 $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
 return $where;
  }
  }
