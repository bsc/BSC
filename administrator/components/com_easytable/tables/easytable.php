<?php
/**
 * @package    EasyTables
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');


/**
 * EasyTable Table class
 *
 * 
 */
class TableEasyTable extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var varchar(255) for the user name of the table
	 */
	var $easytablename = null;
	/**
	 * @var varchar(255) for the user name of the table
	 */
	var $easytablealias = null;
	/**
	 * @var varchar(255) for the description of the table
	 */
	var $description = null;
	/**
	 * @var tinyint(1) for the published state of the table
	 */
	var $published = null;
	/**
	 * @var text for the path to any related images
	 */
	var $defaultimagedir = null;
	/**
	 * @var int for any possible linked table
	 */
	var $linkedtable = null;
	/**
	 * @var datetime of table creation
	 */
	var $created_ = null;
	/**
	 * @var datetime of last table modification
	 */
	var $modified_ = null;
	/**
	 * @var tinyint(3) contains id of last user to modify
	 */
	var $modifiedby_ = null;
	/**
	 * @var checked_out used by Joomla for edit locking
	 */
	var $checked_out = null;
	/**
	 * @var checked_out_time used by Joomla for edit locking
	 */
	var $checked_out_time = null;
	/**
	 * @var hits
	 **/
	 var $hits = null;
	/**
	 * @var datatablename
	 **/
	 var $datatablename = null;
	/**
	 * @var params
	 **/
	 var $params = null;

	/**
		Check function
	 */
	 function check()
	{
		/* Make sure we have an alias for the table - nicer for linking, css etc */
	    jimport( 'joomla.filter.output' );
	    if(empty($this->easytablealias)) {
	            $this->easytablealias = $this->easytablename;
	    }
	    $this->easytablealias = JFilterOutput::stringURLSafe($this->easytablealias);
	 
	    /* Any other checks ?
           Not yet Bob, but ya never know! */
	    return true;
	}

	/**
		Bind function - to support table specific params
	 */
	function bind($array, $ignore = '')
	{
	        if (key_exists( 'params', $array ) && is_array( $array['params'] ))
	        {
	                $registry = new JRegistry();
	                $registry->loadArray($array['params']);
	                $array['params'] = $registry->toString();
	        }
	        return parent::bind($array, $ignore);
	}

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(& $db) {
		parent::__construct('#__easytables', 'id', $db);
	}
}
?>
