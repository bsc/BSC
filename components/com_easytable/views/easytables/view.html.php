<?php
/**
 * @package    EasyTables
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die ('Restricted Access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the EasyTables Component
 *
 * @package    EasyTable
 * @subpackage Views
 */

class EasyTableViewEasyTables extends JView
{
    /**
     * EasyTables view display method
     * @return void
     **/
	function display($tpl = null)
	{
		global $mainframe;
		
		$params =& $mainframe->getParams();
		$show_description = $params->get('show_description',0);
		$page_title = $params->get('page_title','Easy Tables');
		$show_page_title = $params->get('show_page_title',1);
		$pageclass_sfx = $params->get('pageclass_sfx','');
		$sortOrder = (int) JRequest::getVar('table_list_sort_order',0);
		$rows = & $this->get('dataSort'.$sortOrder);

		$this->assignRef('rows', $rows);
		$this->assign('show_description', $show_description);
		$this->assign('page_title', $page_title);
		$this->assign('show_page_title', $show_page_title);

		$this->assign('pageclass_sfx',$pageclass_sfx);

		parent::display($tpl);
	}
}// class
