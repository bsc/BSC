<?php
/**
 * Mcm View for com_mcm Component
 * 
 * @package    Mcm
 * @subpackage com_mcm
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.5
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Mcm Component
 *
 * @package	Joomla.Components
 * @subpackage	Mcm
 */
class McmViewUsers extends JView
{
	function display($tpl = null)
	{
		$data = $this->get('Data');
		$this->assignRef('data', $data);

		parent::display($tpl);
	}
}
?>
