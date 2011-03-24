<?php
/**
 * @package    EasyTables
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');

/**
 * The main uninstaller function
 */
function com_uninstall()
{
	//-- starting values
	$no_errors = TRUE;
	//-- standard text, values & images
	$complete_uninstall = 1;
	$partial__uninstall = 0;
	$img_OK = '<img src="images/publish_g.png" />';
	$img_ERROR = '<img src="images/publish_r.png" />';
	$BR = '<br />';

	//-- common text
	$msg = '<h1>'.JText::_( 'EASYTABLE_UN_INSTALL_PROCESS___' ).'</h1>'.$BR;

	//-- OK, to make the installer aware of our translations we need to explicitly load
	//   the components language file - this should work as the should already be copied in.
    $language = JFactory::getLanguage();
    $language->load('com_easytable');

	//-- first step is this a complete or partial uninstall
	$params = & JComponentHelper::getParams('com_easytable');
	
	if($params->get('uninstall_type') == $partial__uninstall)
	{
		echo $img_OK.JText::_( 'PARTIAL_UNINSTALL___SOFTWARE_ONLY_REMOVED_' ).$BR;
		return TRUE;
	}
	else
	{
		$msg .= $img_OK.JText::_( 'COMPLETE_UNINSTALL___DATA___SOFTWARE_TO_BE_REMOVED_' ).$BR;
	}

	//--get the db object...
	$db = & JFactory::getDBO();

	// Check for a DB connection
	if(!$db){
		$msg .= $img_ERROR.JText::_('UNABLE_TO_CONNECT_TO_DATABASE_').$BR;
		$msg .= $db->getErrorMsg().$BR;
		$no_errors = FALSE;
	}
	else
	{
		$msg .= $img_OK.JText::_('CONNECTED_TO_THE_DATABASE_').$BR;
	}

	// OK DROP the data tables first
	// Select the table id's 
	$et_query = "SELECT `id`, `easytablename` FROM `#__easytables`;";
	$db->setQuery($et_query);
	$data_Table_IDs = $db->loadAssocList();

	$db->query();								// -- adding this to force getNumRows to work
	$num_of_data_tables = $db->getNumRows();	// -- getNumRows() appears to be broken in 1.5 for all other calls

	if($num_of_data_tables)
	{

		if(!($no_errors = $data_Table_IDs))
		{
			$msg .= $img_ERROR.JText::_( 'UNABLE_TO_GET_THE_LIST_OF_DATA_TABLE_ID__S_DURING_THE_UNINSTALL_' ).$BR;
		}
		else
		{
			foreach ( $data_Table_IDs as $item )
			{
				//print_r($item);
				$et_query = 'DROP TABLE `#__easytables_table_data_'.$item['id'].'`;';
				$db->setQuery($et_query);
				$et_drop_result = $db->query();
				// make sure it dropped.
				if(!$et_drop_result)
				{
					$msg .= $img_ERROR.JText::_( 'UNABLE_TO_DROP_DATA_TABLE_' ).' '.$item['easytablename'].' (ID = '.$item['id'].JText::_( '__DURING_THE_UNINSTALL__SQL_____' ).$et_query.' ]'.$BR;
					$no_errors = FALSE;
				}
				else
				{
					$msg .= $img_OK.JText::_( 'SUCCESSFULLY_DROPPED_DATA_TABLE_' ).' '.$item['easytablename'].' (ID = '.$item['id'].').'.$BR;
				}
			}    
		}
	}
	else
	{
		$msg .= $img_OK.JText::_('NO_DATA_TABLES_TO_DROP_'.$BR);
	}


	
	// Now DROP the meta data
	$et_query = 'DROP TABLE `#__easytables_table_meta`;';
	$db->setQuery($et_query);
	$et_drop_result = $db->query();
	// make sure it dropped.
	if(!$et_drop_result)
	{
		$msg .= $img_ERROR.JText::_( 'UNABLE_TO_DROP_META_TABLE_DURING_THE_UNINSTALL_' ).$BR;
		$no_errors = FALSE;
	}
	else
	{
		$msg .= $img_OK.JText::_( 'SUCCESSFULLY_DROPPED_META_TABLE_' ).$BR;
	}
	
	
	// Now DROP the core Tables Database
	$et_query = 'DROP TABLE `#__easytables`;';
	$db->setQuery($et_query);
	$et_drop_result = $db->query();
	// make sure it dropped.
	if(!$et_drop_result)
	{
		$msg .= $img_ERROR.JText::_( 'UNABLE_TO_DROP_CORE_TABLE_DURING_THE_UNINSTALL_' ).$BR;
		$no_errors = FALSE;
	}
	else
	{
		$msg .= $img_OK.JText::_( 'SUCCESSFULLY_DROPPED_CORE_TABLE_' ).$BR;
	}


	if($no_errors)
	{
		$msg .= '<h3>'.JText::_( 'EASYTABLE_UN_INSTALL_COMPLETE___' ).'</h3>'.$BR;
		$msg .= $img_OK.JText::_('EASYTABLE_COMPONENT_REMOVED_SUCCESSFULLY__FAREWELL___IT__S_BEEN_NICE_').$BR;
	}
	else
	{
		$msg .= $img_ERROR.JText::_('EASYTABLE_COMPONENT_REMOVAL_FAILED_____MANUAL_REMOVAL_MAY_BE_REQUIRED___').$BR;
	}
	
	echo $msg;
	return $no_errors;
}// function
