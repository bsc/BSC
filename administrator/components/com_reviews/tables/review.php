<?php
defined('_JEXEC') or die('Restricted access');

class TableReview extends JTable
{
    var $id = null;
    var $name = null;
    var $address = null;
    var $reservations = null;
    var $quicktake = null;
    var $review = null;
    var $notes = null;
    var $smoking = null;
    var $credit_cards = null;
    var $cuisine = null;
    var $avg_dinner_price = null;
    var $review_date = null;
    var $published = null;

    function __construct(&$db)
    {
        parent::__construct( '#__reviews', 'id', $db );
    }
}
?>

