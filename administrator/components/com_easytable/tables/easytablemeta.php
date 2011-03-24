<?php
	defined('_JEXEC') or die('Restricted Access');
	class TableEasyTableMeta extends JTable
	{
		var $id = null;
		var $easytable_id = null;
		var $position = null;
		var $label = null;
		var $description = null;
		var $type = null;
		var $list_view = null;
		var $detail_link = null;
		var $detail_view = null;
		var $fieldalias = null;
		
		function __construct(&$db)
		{
			parent::__construct('#__easytables_table_meta', 'id', $db);
		}
	}
?>