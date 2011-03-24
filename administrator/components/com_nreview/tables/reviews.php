<?php
/**
 * Nreview Model for Nreview Component
 * 
 * @package    Nreview
 * @subpackage com_nreview
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.5
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Nreview Table
 *
 * @package    Joomla.Components
 * @subpackage 	Nreview
 */
class TableReviews extends JTable{
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
	var $address = null;
	/**
	 *
	 * @var string
	 */
	var $reservations = null;
	/**
	 *
	 * @var string
	 */
	var $quicktake = null;
	/**
	 *
	 * @var string
	 */
	var $review = null;
	/**
	 *
	 * @var string
	 */
	var $notes = null;
	/**
	 *
	 * @var int
	 */
	var $smoking = 0;
	/**
	 *
	 * @var string
	 */
	var $credit_cards = null;
	/**
	 *
	 * @var string
	 */
	var $cuisine = null;
	/**
	 *
	 * @var int
	 */
	var $avg_dinner_price = 0;
	/**
	 *
	 * @var datetime
	 */
	var $review_date = null;
	/**
	 *
	 * @var int
	 */
	var $published = 0;
	/** jcb code */

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableReviews(& $db){
		parent::__construct('#__reviews', 'id', $db);
	}
	
	function check(){
		// write here data validation code
		return parent::check();
	}
}