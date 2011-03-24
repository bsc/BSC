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
 * Mcm View
 *
 * @package    Joomla.Components
 * @subpackage 	Mcm
 */
class McmViewUserslist extends JView
{
	/**
	 * Userslist view display method
	 * @return void
	 **/
	function display($tpl = null){
		$app =& JFactory::getApplication();
		$user  = JFactory::getUser();

		// Get data from the model
		$rows = & $this->get( 'Data');
		
		// draw menu
		//'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		JToolBarHelper::title( JText::_( 'MCM_MANAGER' ), 'generic.png' );
		if($user->authorise('core.edit', 'com_mcm')) JToolBarHelper::editListX();
		if($user->authorise('core.create', 'com_mcm')) JToolBarHelper::addNewX();
		if($user->authorise('core.delete', 'com_mcm')) JToolBarHelper::deleteList();
		
		if( (isset($rows[0]->published)) && ($user->authorise('core.edit', 'com_mcm')) ){
			JToolBarHelper::divider();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}
		
		// configuration editor for config.xml
		if($user->authorise('core.admin', 'com_mcm')){
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_mcm');
		}
		

		$this->assignRef('rows', $rows );
		$pagination =& $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		// SORTING get the user state of order and direction
		$default_order_field = 'id';
		$lists['order_Dir'] = $app->getUserStateFromRequest('com_mcmfilter_order_Dir', 'filter_order_Dir', 'ASC');
		$lists['order'] = $app->getUserStateFromRequest('com_mcmfilter_order', 'filter_order', $default_order_field);
		$lists['search'] = $app->getUserStateFromRequest('com_mcmsearch', 'search', '');
		$this->assignRef('lists', $lists);


		parent::display($tpl);
	}
}