<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_easytable'.DS.'tables');

class EasyTableViewEasyTable extends JView
{
	function display ($tpl = null)
	{
		global $mainframe, $option;
		// Better breadcrumbs
		$pathway   =& $mainframe->getPathway();
		$id = (int) JRequest::getVar('id',0);
		// For a better backlink - lets try this:
		$start_page = JRequest::getVar('start',0,'','int');                 // get the start var from JPagination
		$mainframe =& JFactory::getApplication();                           // get the app
        $mainframe->setUserState( "$option.start_page", $start_page );      // store the start page

		// Get Params
		global $mainframe;

		$params =& $mainframe->getParams(); // Component wide & menu based params
//         echo '<BR />Echoing Compoent Wide & Menu $params<BR />';
//         echo print_r ( $params );
//         echo '<BR />';

        // Get the table based on the id from the request - we do it here so we can merge the tables params in.
		$easytable =& JTable::getInstance('EasyTable','Table');
		$easytable->load($id);
		if($easytable->published == 0) {
			JError::raiseError(404,JText::_( "THE_TABLE_YOU_REQUESTED_IS_NOT_PUBLISHED_OR_DOESN_T_EXIST_BR___RECORD_ID__" ).$id);
		}
		
        $params->merge( new JParameter( $easytable->params ) );// Merge them with specific table based params
//         echo '<BR />Echoing $params for just this table:<BR />';
//         echo print_r ( $params );
//         echo '<BR />';

		$show_description = $params->get('show_description',0);
		$show_search = $params->get('show_search',0);
		$show_with_pagination = $params->get('show_with_pagination',0);
		$show_pagination_header = $params->get('show_pagination_header',0);
		$show_pagination_footer = $params->get('show_pagination_footer',0);
		$show_created_date = $params->get('show_created_date',0);
		$show_modified_date = $params->get('show_modified_date',0);
		$modification_date_label = $params->get('modification_date_label','');
		$show_page_title = $params->get('show_page_title',0);
		$pageclass_sfx = $params->get('pageclass_sfx','');

		$pathway->addItem($easytable->easytablename, 'index.php?option='.$option.'&id='.$id.'&start='.$start_page);
		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		// Get the menu item object
		$menus = &JSite::getMenu();
		$menu  = $menus->getActive();

		if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'easytable' && isset($menu->query['id']) && $menu->query['id'] == $id) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title', $easytable->easytablename);
			}
		} else {
			$params->set('page_title', $easytable->easytablename);
		}
		$page_title = $params->get( 'page_title' );

        // Get the default image directory from the table.
		$imageDir = $easytable->defaultimagedir;

		// Get a database object
		$db =& JFactory::getDBO();
		if(!$db){
			JError::raiseError(500,JText::_( "COULDN_T_GET_THE_DATABASE_OBJECT_WHILE_GETTING_EASYTABLE_ID__" ).$id);
		}
		// Get the meta data for this table
		$query = "SELECT label, fieldalias, type, detail_link, description FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id =".$id." AND list_view = '1' ORDER BY position;";
		$db->setQuery($query);
		
		$easytables_table_meta = $db->loadRowList();
		$etmCount = count($easytables_table_meta); //Make sure at least 1 field is set to display
		
		if($etmCount)  //Make sure at least 1 field is set to display
		{
			// Get paginated table data
			if($show_with_pagination)
			{
				$paginatedRecords =& $this->get('data');
			}
			else
			{
				$paginatedRecords =& $this->get('alldata');
			}

			// Get pagination object
			$pagination =& $this->get('pagination');
		}
		else
		{
			$easytables_table_meta = array(array("Warning EasyTable List View Empty","","","",""));
			$paginatedRecords = array(array("id" => 0, "Message" => "No fields selceted to display in list view for this table"));
		}

		// Search
		$search = $db->getEscaped($this->get('search'));
        //Get form link
        $paginationLink = JRoute::_('index.php?option=com_easytable&view=easytable&id='.$id.':'.$easytable->easytablealias);


		
		// Assing these items for use in the tmpl
		$this->assign('show_description', $show_description);
		$this->assign('show_search', $show_search);
		$this->assign('show_with_pagination', $show_with_pagination);
		$this->assign('show_pagination_header', $show_pagination_header);
		$this->assign('show_pagination_footer', $show_pagination_footer);

		$this->assign('show_created_date', $show_created_date);
		$this->assign('show_modified_date', $show_modified_date);
		$this->assign('modification_date_label', $modification_date_label);

		$this->assign('show_page_title', $show_page_title);
		$this->assign('page_title', $page_title);
		$this->assign('pageclass_sfx',$pageclass_sfx);

		$this->assign('tableId', $id);
		$this->assign('imageDir', $imageDir);
		$this->assignRef('easytable', $easytable);
		$this->assignRef('easytables_table_meta', $easytables_table_meta);
		$this->assignRef('pagination', $pagination);
		$this->assign('paginationLink', $paginationLink);
		$this->assignRef('paginatedRecords', $paginatedRecords);
		$this->assign('search',$search);
		$this->assign('etmCount', $etmCount);
		parent::display($tpl);
	}
}
?>
