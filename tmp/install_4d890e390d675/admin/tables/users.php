<?php
/**
 * Mcm Model for Mcm Component
 * 
 * @package    Mcm
 * @subpackage com_mcm
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.6
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Mcm Table
 *
 * @package    Joomla.Components
 * @subpackage 	Mcm
 */
class TableUsers extends JTable{
	/** jcb code */
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	/**
	 *
	 * @var string
	 */
	var $name = null;
	/**
	 *
	 * @var string
	 */
	var $last_name = null;
	/**
	 *
	 * @var string
	 */
	var $username = null;
	/**
	 *
	 * @var string
	 */
	var $email = null;
	/**
	 *
	 * @var string
	 */
	var $password = null;
	/**
	 *
	 * @var string
	 */
	var $usertype = null;
	/**
	 *
	 * @var int
	 */
	var $block = 0;
	/**
	 *
	 * @var int
	 */
	var $sendEmail = 0;
	/**
	 *
	 * @var int
	 */
	var $gid = 1;
	/**
	 *
	 * @var datetime
	 */
	var $registerDate = "0000-00-00 00:00:00";
	/**
	 *
	 * @var datetime
	 */
	var $lastvisitDate = "0000-00-00 00:00:00";
	/**
	 *
	 * @var string
	 */
	var $activation = null;
	/**
	 *
	 * @var string
	 */
	var $params = null;
	/** jcb code */

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableUsers(& $db){
		parent::__construct('#__users', 'id', $db);
	}
	
	function check(){
		// write here data validation code
		return parent::check();
	}
}