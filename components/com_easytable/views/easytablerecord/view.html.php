<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_easytable'.DS.'tables');

class EasyTableViewEasyTableRecord extends JView
{
	function getFieldAliasForMetaID ($mId = 0)
	{
		if($mId)
		{
			$db =& JFactory::getDBO();
			if(!$db){
				JError::raiseError(500,JText::_( "COULDN_T_GET_THE_DATABASE_OBJECT_WHILE_GETTING_A_LINKED_TABLE_FIELD_ALIAS_" ).$mId );
			}
			$fafmID_query = "SELECT fieldalias FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE id = $mId";

			$db->setQuery($fafmID_query);
			
			return($db->loadResult());
		}
		return FALSE;
	}
	
	function &fieldMeta($id, $restrict_to_view ='')
	{
		$db =& JFactory::getDBO();
		if(!$db){
			JError::raiseError(500, JText::_( "COULDN_T_GET_THE_DATABASE_OBJECT_WHILE_GETTING_FIELD_ALIAS_S" ));
		}
		if($restrict_to_view == '')
		{
			$query = "SELECT label, fieldalias, type, detail_link, description, id, detail_view, list_view FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id ='$id' ORDER BY position;";
		}
		else
		{
			$query = "SELECT label, fieldalias, type, detail_link, description, id, detail_view, list_view FROM ".$db->nameQuote('#__easytables_table_meta')." WHERE easytable_id ='$id' AND `$restrict_to_view` = '1' ORDER BY position;";
		}

		$db->setQuery($query);
		$meta = $db->loadRowList();
		return $meta;
	}
	
	function fieldAliassForDetail($metaArray, $lkf_id)
	{
		return($this->fieldAliass($metaArray, $lkf_id, 6));
	}
	function fieldAliassForList($metaArray, $lkf_id)
	{
		return($this->fieldAliass($metaArray, $lkf_id, 7));
	}
	function fieldAliass($metaArray, $lkf_id, $ListOrDetailSelector)
	{
		// Convert the list of meta records into the list of fields that can be used in the SQL
		$fields = array();
		$fields[] = 'id'; //put the id in first for accessing detail view of a table row

		foreach($metaArray as $aRow) 
		{
			if(($aRow[5] == $lkf_id) || ($aRow[$ListOrDetailSelector] == '1'))
			{
				$fields[] .= $aRow[1]; // compile a list of the fieldalias'
			}
		}
		return($fields);
	}
	
	function fieldLabelsForDetail($metaArray, $lkf_id)
	{
		return ($this->fieldLabels($metaArray, $lkf_id, 6));
	}
	function fieldLabelsForList($metaArray, $lkf_id)
	{
		return ($this->fieldLabels($metaArray, $lkf_id, 7));
	}
	function fieldLabels($metaArray, $lkf_id, $ListOrDetailSelector)
	{
		// Convert the list of meta records into the list of fields labels
		$labels = array();
		$labels[] = 'id'; //put the id in first for accessing detail view of a table row

		foreach($metaArray as $aRow) 
		{
			if(($aRow[5] == $lkf_id) || ($aRow[$ListOrDetailSelector] == '1'))
			{
				$labels[] .= $aRow[0]; // compile a list of the field labels
			}
		}
		return($labels);
	}
	
	function fieldTypes($metaArray)
	{
		// Convert the list of meta records into the list of fields types
		$types = array();
		$types[] = 'id'; //put the id in first for accessing detail view of a table row

		foreach($metaArray as $aRow) 
		{
			$types[] .= $aRow[2]; // compile a list of the field types
		}
		return($types);
	}
	
	function fieldDetailLink($metaArray)
	{
		// Convert the list of meta records into the list of Detail Link flags for the fields
		$types = array();
		$types[] = 'id'; //put the id in first for accessing detail view of a table row

		foreach($metaArray as $aRow) 
		{
			$types[] .= $aRow[3]; // compile a list of the detail link flags
		}
		return($types);
	}
	
	function display ($tpl = null)
	{
		$debugMsg = '';
		// Get the table id and the record id
		$id = (int) JRequest::getVar('id',0);
		$rid = (int) JRequest::getVar('rid',0);

		/*
		 *
		 * Get the current ET details and make sure it's published.
		 *
		 */
		$easytable =& JTable::getInstance('EasyTable','Table');
		$easytable->load($id);
		if($easytable->published == 0) {
			JError::raiseError(404, JText::_( "THE_TABLE_RECORD_YOU_REQUESTED_IS_NOT_PUBLISHED_OR_DOESN_T_EXIST_BR___RECORD_ID__" ).$id.' / '.$rid);
		}
		
		$imageDir = $easytable->defaultimagedir;


		/*
		 * Get Params for linked tables as we'll need them soon
		 */
		global $mainframe;
		$params = $mainframe->getParams(); // Component wide & menu based params
		$params->merge( new JParameter( $easytable->params ) ); // Merge with this tables params
		$lt_id = $params->get('id',0);
		$kf_id = $params->get('key_field',0);
		$lkf_id = $params->get('linked_key_field',0);

		/*
		 *
		 * Get the META records for this EasyTable and use them to create sql for data table selection
		 *
		 */
		$db =& JFactory::getDBO();
		if(!$db){
			JError::raiseError(500,JText::_( "COULDN_T_GET_THE_DATABASE_OBJECT_WHILE_GETTING_EASYTABLE_ID__" ).$id);
		}
		// Get the meta data for this table
		$easytables_table_meta = $this->fieldMeta($id);

		// Convert the list of meta records into the list of fields that can be used in the SQL
		// the basic row list must be filtered for the detail view
		$fields = implode('`, `', $this->fieldAliassForDetail($easytables_table_meta, $kf_id) );

		/*
		 *
		 * Get the specific DATA record using sql of detail_view fields
		 *
		 */
		$query = "SELECT `".$fields."` FROM ".$db->nameQuote('#__easytables_table_data_'.$id)." WHERE id=$rid;";
		$db->setQuery($query);
		$easytables_table_record =$db->loadRow();
		$db->setQuery($query);
		$et_tr_assoc = $db->loadAssoc();

		/*
		 *
		 * If there is a Linked Table we need to assemble the SQL
		 * and extract the related records.
		 *
		 */
		// Using the linked table bits assemble the SQL to get the related records
		if($lt_id)
		{
			// First get the fieldalias of the Key_Field ie. the col name in the primary table
			$kf_alias = $this->getFieldAliasForMetaID($kf_id);
			
			// From the record for the primary table get the value to match against in the linked table
			$kf_search_value = $et_tr_assoc[$kf_alias];
			
			$lkf_alias = $this->getFieldAliasForMetaID($lkf_id); // Get the alias (column name) of the linked key field

			$linked_table_meta = $this->fieldMeta($lt_id,'list_view');
			
			$linked_fields_to_get = implode('`, `', $this->fieldAliassForList($linked_table_meta,$lkf_id) );
			
			$linked_records_SQL = "SELECT `$linked_fields_to_get` FROM `#__easytables_table_data_$lt_id` WHERE `$lkf_alias` = '$kf_search_value'";
			
			$db->setQuery($linked_records_SQL);
			$linked_records = $db->loadAssocList();
			
			$tableHasRecords = count($linked_records);

			$this->assign('tableHasRecords', $tableHasRecords);
			if($tableHasRecords)
			{
				$linked_easytable =& JTable::getInstance('EasyTable','Table');
				$linked_easytable->load($lt_id);
				
				$linked_easytable_alias = $linked_easytable->easytablealias; // We get the alias for use in the table id
				$this->assign('linked_easytable_alias',$linked_easytable_alias);
				
				$linked_easytable_description = $linked_easytable->description; // The description to use it as the 'summary' value in the <table>
				$this->assign('linked_easytable_description',$linked_easytable_description);
				
				$linked_table_imageDir = $linked_easytable->defaultimagedir;   // We use this to prepend all image type data
				$this->assign('linked_table_imageDir', $linked_table_imageDir );
				
				$linked_field_types =& $this->fieldTypes($linked_table_meta);  // Heading, types and other meta for the linked table
				$this->assignRef('linked_field_types', $linked_field_types );
				
				$linked_field_links_to_detail =& $this->fieldDetailLink($linked_table_meta); // Flags for the detail link
				$this->assignRef('linked_field_links_to_detail', $linked_field_links_to_detail);
				
				$linked_fields_alias = $this->fieldAliassForList($linked_table_meta,$lkf_id);  // Field alias for use in CSS class for each field
				$this->assignRef('linked_fields_alias', $linked_fields_alias );
				

				$linked_field_labels =& $this->fieldLabelsForList($linked_table_meta,$lkf_id); // Labels/field headings for use in table
				$this->assignRef('linked_field_labels', $linked_field_labels );
				
				$this->assignRef('linked_records', $linked_records );
			}
		}


		// Create a backlink
		global $mainframe, $option;
        $mainframe =& JFactory::getApplication();
        $start_page = $mainframe->getUserState( "$option.start_page", 0 );
		$backlink = "index.php?option=com_easytable&view=easytable&id=$id:$easytable->easytablealias&start=$start_page";
		$backlink = JRoute::_($backlink);

		// Setup the rest of the params related to display
		$show_description = $params->get('show_description',0);
		$show_created_date = $params->get('show_created_date',0);
		$show_modified_date = $params->get('show_modified_date',0);

		$pageclass_sfx = $params->get('pageclass_sfx','');

		
		// Assing these items for use in the tmpl
		$this->assign('show_description', $show_description);
		$this->assign('show_created_date', $show_created_date);
		$this->assign('show_modified_date', $show_modified_date);
		$this->assign('linked_table', $lt_id);

		$this->assign('pageclass_sfx',$pageclass_sfx);

		$this->assign('tableId', $id);
		$this->assignRef('imageDir', $imageDir);
		$this->assignRef('backlink', $backlink);
		$this->assignRef('easytable',$easytable);
		$this->assignRef('easytables_table_meta',$easytables_table_meta);
		$this->assignRef('easytables_table_record',$easytables_table_record);
		parent::display($tpl);
	}
}
