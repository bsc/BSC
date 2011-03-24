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
 * @package		Nreview
 * @subpackage	Components
 */
class NreviewViewReviewslist extends JView
{
	function display($tpl = null){
		$app =& JFactory::getApplication();
		/*
		$params =& JComponentHelper::getParams( 'com_nreview' );
		$params =& $app->getParams( 'com_nreview' );	
		$dummy = $params->get( 'dummy_param', 1 ); 
		*/
	
		$data =& $this->get('Data');
		$this->assignRef('data', $data);
		
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}
?>
