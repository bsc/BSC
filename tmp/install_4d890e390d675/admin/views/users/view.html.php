<?php
/**
 * Mcm View for Mcm Component
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

jimport( 'joomla.application.component.view' );

/**
 * Mcm view
 *
 * @package    Joomla.Components
 * @subpackage 	Mcm
 */
class McmViewUsers extends JView
{
	/**
	 * display method of Mcm view
	 * @return void
	 **/
	function display($tpl = null){
		$user  = JFactory::getUser();
		
		//get the data
		$data =& $this->get('Data');
		$isNew = ($data->id == null);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		JToolBarHelper::title(   JText::_( 'MCM' ).': <small>[ ' . $text.' ]</small>' );
		
		if ($isNew)  {
			if($user->authorise('core.create', 'com_mcm')) JToolBarHelper::save();
			JToolBarHelper::cancel();
		} else {
			if($user->authorise('core.edit', 'com_mcm')) JToolBarHelper::save();
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );
		}

		$this->assignRef('data', $data);
		
		// create options for 'select' used in template
		$dataOptions = array();
		foreach(explode(',', '') as $field){
			if (!$field) continue;
			//options array are generated in the model...
			$dataOptions[$field] =& $this->get( ucfirst($field) );
		}
		
		/*
		// related table example 
		// thisTableFieldKey : foreign key (es #__content.catid -> 'catid')
		// relatedTableModelList : name used for table holding data (es #__content -> 'contentlist')
		// getRelatedTableFieldData : method for getting related table values for key (es #__categories.title -> 'getTitleFieldData()')
		// REMEMBER to add model inclusion in controller recordset list
		// see http://www.mmleoni.net/joomla-component-builder/create-joomla-extensions-manage-the-back-end-part-2

		$rmodel =& $this->getModel('relatedTableModelList'); 
		$dataOptions['thisTableFieldKey'] =& $rmodel->getRelatedTableFieldData();
		*/

		
		$this->assignRef('dataOptions', $dataOptions);

		parent::display($tpl);
	}
}