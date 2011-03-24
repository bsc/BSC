<?php
//defined( '_VALID_MOS' ) or die( 'Restricted access' );
if (!(defined( '_VALID_MOS' ) || defined( '_JEXEC' ))) die( 'Restricted access1' );

// разрешим доступ только пользователям с правами супер-администратора
/*
if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
        mosRedirect( 'index2.php', _NOT_AUTH );
}
*/
/*
if ( !( $acl->acl_check('administration', 'config', 'users', $my->usertype) ) 
	||  $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_easysql') ) {
	mosRedirect( 'index2.php', _NOT_AUTH );
}
*/


if (defined('_JEXEC')) {
	$cms = 'j';
	$lang =& JFactory::getLanguage();
	$mosConfig_alang = $lang->getTag();
	$database =& JFactory::getDBO();
	if ( ! function_exists('mosGetParam')) {
		function mosGetParam( $hash, $name, $default ) {
			return JRequest::getVar($name, $default, $hash);
		}
	}
}
elseif (defined('_VALID_MOS')) {
	$cms = 'm';
	$mosConfig_alang = $mosConfig_lang;
}

// include language file
$lang_path = dirname(__FILE__).'/lang';
if (file_exists ("$lang_path/$mosConfig_alang.php")) include_once ("$lang_path/$mosConfig_alang.php");
else include_once("$lang_path/english.php");

// include html body
require_once( $mainframe->getPath( 'admin_html' ) );

// read params
$task   = mosGetParam( $_GET, 'task', '' );
$task   = empty($task) ? mosGetParam( $_POST, 'task', 'execsql' ) : $task;
$id     = mosGetParam( $_GET, 'id', null );
$table  = base64_decode(mosGetParam( $_GET, 'prm1', null ));
$sql    = mosGetParam( $_POST, 'easysql_query', null );
if (empty($table)) $table = mosGetParam( $_POST, 'easysql_table', null );

switch ($task) {
        case 'tocsv' :
                ExportCSV($table, $sql, $database);
                //$url = $mosConfig_live_site.'/administrator/components/com_easysql/export.easysql.php?prm1=csv&prm2='.$cms.'&prm3='.base64_encode($table).'&prm4='.base64_encode($sql);
                //echo "<script>document.location.href='$url';</script>\n";
                break;

        case 'new' :
        case 'edit' :
                EditRecord($task, $table, $id, $database);
                break;

        case 'delete' :
                if (!is_null($id)&&!is_null($table))
                        if (DeleteRecord($table, $id, $database)) ExecSQL($task, $database);
                break;

        case 'save' :
                if (SaveRecord($database)) ExecSQL($task, $database);
                break;

        case 'create' :
                if (InsertRecord($database)) ExecSQL($task, $database);
                break;

        default :
                ExecSQL($task, $database);
                break;
}


echo _ES_COPYRIGHT;



function ExportCSV($table, $sql, $database)
{
        ob_end_clean();
        
        $file_name = 'export_'.$table.'.csv';
        
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Accept-Ranges: bytes');
        header('Content-Disposition: attachment; filename='.basename($file_name).';');
        header('Content-Type: text/plain; '._ISO);
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Pragma: no-cache');
        
        echo ExportToCSV($sql, $database);
        die();  // no need to send anything else
}

////////////////////////////////////////////////////////////////
// Export table to CSV format
////////////////////////////////////////////////////////////////
function ExportToCSV($sql, $database)
{
        $csv_save = '';
        $database->setQuery( $sql );
        $rows = @$database->loadAssocList();
        if (!empty($rows)) {
                $comma = _ES_CSV_DELIMITER;
                $CR = "\r";
                // Make csv rows for field name
                $i=0;
                $fields = $rows[0];
                $cnt_fields = count($fields);
                $csv_fields = '';
                foreach($fields as $name=>$val) {
                        $i++;
                        if ($cnt_fields<=$i) $comma = '';
                        $csv_fields .= $name.$comma;
                }
                // Make csv rows for data
                $csv_values = '';
                foreach($rows as $row) {
                        $i=0;
                        $comma = _ES_CSV_DELIMITER;
                        foreach($row as $name=>$val) {
                                $i++;
                                if ($cnt_fields<=$i) $comma = '';
                                $csv_values .= $val.$comma;
                        }
                        $csv_values .= $CR;
                }
                $csv_save = $csv_fields.$CR.$csv_values;
        }
        return $csv_save;
}

?>