<?php
/**
 * Nreview View for com_nreview Component
 * 
 * @package    Nreview
 * @subpackage com_nreview
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.5
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Nreview Component
 *
 * @package	Joomla.Components
 * @subpackage	Nreview
 */
class NreviewViewReviews extends JView
{
	function display($tpl = null)
	{
		$data = $this->get('Data');
		$this->assignRef('data', $data);

		parent::display($tpl);
	}
}
?>
