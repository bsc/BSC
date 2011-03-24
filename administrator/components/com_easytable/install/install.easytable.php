<?php
/**
 * @package    EasyTables
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');

/**
 * Main installer
 */
function com_install()
{
	$no_errors = TRUE;
	
	//-- common images
	$img_OK = '<img src="images/publish_g.png" />';
	$img_ERROR = '<img src="images/publish_r.png" />';
	//-- common text
	$msg = '';
	$BR = '<br />';
	
	//-- OK, to make the installer aware of our translations we need to explicitly load
	//   the components language file - this should work as the should already be copied in.
    $language = JFactory::getLanguage();
    $language->load('com_easytable');

	//--get the db object...
	$db = & JFactory::getDBO();

	// Check for a DB connection
	if(!$db){
		$msg .= $img_ERROR.JText::_( 'UNABLE_TO_CONNECT_TO_DATABASE_' ).$BR;
		$msg .= $db->getErrorMsg().$BR;
		$no_errors = FALSE;
	}
	else
	{
		$msg .= $img_OK.JText::_( 'CONNECTED_TO_THE_DATABASE_' ).$BR;
	}
	
	// Get the list of tables in $db
	$et_table_list =  $db->getTableList();
	if(!$et_table_list)
	{
		$msg .= $img_ERROR.JText::_( 'COULDN__T_GET_LIST_OF_TABLES_IN_DATABASE_FOR_INSTALL_' ).$BR;
		$no_errors = FALSE;
	} else {
			$msg .= $img_OK.JText::_( 'SUCCESSFULLY_RETREIVED_LIST_OF_TABLES_IN_DATABASE_' ).$BR;
	}

	// Check for the core table
	if(!in_array($db->getPrefix().'easytables', $et_table_list))
	{
		$msg .= $img_ERROR.JText::_( 'CORE_EASYTABLE_TABLE_NOT_FOUND_' ).$BR;
		$msg .= $db->getErrorMsg().$BR;
		$no_errors = FALSE;
	} else {
			$msg .= $img_OK.JText::_( 'EASYTABLE_CORE_TABLE_SETUP_SUCCESSFUL_' ).$BR;
	}

	// Check for the metadata table
	if(!in_array($db->getPrefix().'easytables_table_meta',$et_table_list))
	{
		$msg .=  $img_ERROR.JText::_( 'UNABLE_TO_FIND_META_TABLE' ).$BR;
		$msg .=  $db->getErrorMsg().$BR;
		$no_errors = FALSE;
	} else {
			$msg .= $img_OK.JText::_( 'EASYTABLE_META_TABLE_SETUP_SUCCESSFUL_' ).$BR;
	}
	
	// Check perform any table upgrades in this last section.
	// 1. Remove the column for the 'showsearch' parameter
	//-- See if the column exists --//
	$tableFieldsResult = $db->getTableFields('#__easytables');
	$columnNames = $tableFieldsResult['#__easytables'];

	if(array_key_exists('showsearch', $columnNames))
	{
		$msg .= $img_ERROR.JText::_( 'EASYTABLES_HAS_COLUMN__SHOWSEARCH__' ).$BR;
		$et_updateQry = "ALTER TABLE #__easytables DROP COLUMN `showsearch`;";
		$db->setQuery($et_updateQry);
		$et_updateResult = $db->query();
		if(!$et_updateResult)
		{
			$msg .= $img_ERROR.JText::_( 'ALTER_TABLE_FAILED_FOR_COLUMN__SHOWSEARCH__' ).$BR;
			$no_errors = FALSE;
		}
		else
		{
			$msg .= $img_OK.JText::_( 'EASYTABLES_UPDATED___SUCCESSFULLY_REMOVED_COLUMN__SHOWSEARCH__' ).$BR;
		}
	}
	else
	{
		$msg .= $img_OK.JText::_( 'EASYTABLE_TABLE_STRUCTURES_ARE_UP_TO_DATE_' ).$BR;
	}

	// If all is good so far we can get the current version.
	if($no_errors)
	{
		// Must break out version function in view to a utility class - ** must setup a utility class ** doh!
		// No doubt this will end in grief then we'll fix it but for now version is in 2 places.... time, time, oh for more time....
		// See - the lack of time did bite you - now you're undoing the work from the last version... make more time!
		$et_this_version = '1.0.5a';
		//
		
		// Update the version entry in the Table comment to the current version.
		$et_updateQry = "ALTER TABLE #__easytables COMMENT='".$et_this_version."'";
		$db->setQuery($et_updateQry);
		$et_updateResult = $db->query();
		if(!$et_updateResult)
		{
			$msg .= $img_ERROR.JText::_( 'COULDN__T_UPDATE_VERSION_IN_TABLE_COMMENT_' ).$BR;
			$no_errors = FALSE;
		}
		else
		{
			$msg .= $img_OK.JText::_( 'EASYTABLES_UPDATED_VERSION_IN_TABLE_COMMENT_' ).$BR;
		}
	}

	// Ok, lets append the wrap message and get the heck outta here.
	if($no_errors)
	{
		$msg .= $img_OK.JText::_( 'EASYTABLE_INSTALLATION_SUCCESSFUL_' ).$BR;
	}
	else
	{
		$msg .= $img_ERROR.'<span style="color:red;">'.JText::_( 'EASYTABLE_INSTALLATION_FAILED_' ).'</span>'.$BR;
	}

	echo $msg;
	return $no_errors;
}// function
