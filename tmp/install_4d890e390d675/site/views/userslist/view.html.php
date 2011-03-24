<?php
/**
 * Mcm View for com_mcm Component
 * 
 * @package    Mcm
 * @subpackage com_mcm
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.6
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Mcm Component
 *
 * @package		Mcm
 * @subpackage	Components
 */
class McmViewUserslist extends JView
{
	function display($tpl = null){
		$app =& JFactory::getApplication();
		/*
		$params =& JComponentHelper::getParams( 'com_mcm' );
		$params =& $app->getParams( 'com_mcm' );	
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
