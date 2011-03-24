<?php
//defined( '_VALID_MOS' ) or die( 'Restricted access' );
if (!(defined( '_VALID_MOS' ) || defined( '_JEXEC' ))) die( 'Restricted access2' );

////////////////////////////////////////////////////////////////
// Execute sql query and display grid
////////////////////////////////////////////////////////////////
function ExecSQL($task='execsql', $database) {
	global $mosConfig_dbprefix;

       $nDisplayRecords        = intval( mosGetParam( $_POST, 'easysql_records', 10 ) );

       if ($task=='execsql') {
               $cCurrentTable  = mosGetParam( $_POST, 'easysql_table', '' );
               $cCurrentSQL    = mosGetParam( $_POST, 'easysql_query', mosGetParam( $_GET, 'prm2', '' ));
               $cCurrentSQL    = stripslashes($cCurrentSQL);
       } else {
               $cCurrentTable  = base64_decode(mosGetParam( $_POST, 'easysql_table', '' ));
               $cCurrentSQL    = base64_decode(mosGetParam( $_POST, 'easysql_query', mosGetParam( $_GET, 'prm2', '' )) );
               $cCurrentSQL    = stripslashes($cCurrentSQL);
       }

       // Get table list
       $database->setQuery( 'SHOW TABLES' );
       $tables = $database->loadAssocList();

       if (!empty($tables)) {
               if (defined('_VALID_MOS')) {
		   global $mosConfig_db;
                   $db = $mosConfig_db;
               } else {
                   $conf =& JFactory::getConfig();
                   $db = $conf->getValue('config.db');
               }
               $key = 'Tables_in_'.$db;
               $htmTablesList = '';
               foreach($tables as $val) {
                       if ($val[$key] == $cCurrentTable) $_sel = 'selected'; else $_sel = '';;
                       $htmTablesList .= '<OPTION '.$_sel.' VALUE="'.$val[$key].'">'.$val[$key].'</OPTION>';
               }
       }

       // Header
       $htmTableHeader = '';
       if (!empty($cCurrentSQL)) {
               $database->setQuery( $cCurrentSQL );
               $rows = $database->loadAssocList();
               if (!empty($rows)) {
                       $aTableHeader = array();
                       //$htmTableHeader = "";
                       foreach($rows[0] as $key=>$val) {
                               $aTableHeader[] = $key;
                               //$htmTableHeader .= '<th>'.$key.'</th>';
                       }
               }
       } else $rows = array();

       $htmTableData='';
       if(!empty($cCurrentSQL)) {

               if (preg_match('/REPLACE PREFIX (.*) TO (.*)/', $cCurrentSQL)) {
                   replace_prefix( $database, $db, $cCurrentSQL );
               } else {
                   $query_arr = split_sql($cCurrentSQL);
                   for($i=0;$i<=(count($query_arr)-1);$i++) {
                           if(trim($query_arr[$i]) != '')$htmTableData.=record_html($query_arr[$i],$i, $database);
                   }
               }
       }

    $isJ15 = defined( '_JEXEC' );
    if ($isJ15) {
        $prefix = $database->getPrefix();
    } else {
        $prefix = $mosConfig_dbprefix;
    }

?>

<script language="javascript" type="text/javascript">
<!--
function changeQuery(thiz) {
       limit = 'LIMIT ' + document.getElementById('easysql_records').value;
       sel = document.getElementById('easysql_sel').value;
       if (sel!='SELECT * FROM ') limit='';
       table = '';
       if (sel=='SELECT * FROM table_name PROCEDURE ANALYSE() ') {
               table=document.getElementById('easysql_table').value;
               document.getElementById('easysql_query').value='SELECT * FROM '+table+' PROCEDURE ANALYSE()';
               return;
       }
       if     (sel=='SELECT * FROM ' ||
               sel=='SHOW KEYS FROM ' ||
               sel=='SHOW FIELDS FROM ' ||
               sel=='REPAIR TABLE ' ||
               sel=='OPTIMIZE TABLE ' ||
               sel=='CHECK TABLE ' ||
               sel=='SHOW FULL COLUMNS FROM ' ||
               sel=='SHOW INDEX FROM ' ||
               sel=='SHOW TABLE STATUS ' ||
               sel=='SHOW CREATE TABLE ' ||
               sel=='ANALYZE TABLE ')
               table=document.getElementById('easysql_table').value+' '+limit;
       document.getElementById('easysql_query').value=sel+table;
}
//-->
</script>

<form id="adminForm" action="index2.php?option=com_easysql" method="post" name="adminForm">
<table border=0 cellsppacing=0 cellpadding=5 width=100%>
<tr>
       <td>
               <?php echo _ES_COMMAND;?><select id="easysql_sel" class="text_area" onchange="changeQuery(this);">
               <OPTGROUP label="SQL commands">
                   <option value="SELECT * FROM ">SELECT *</option>
                   <option value="SHOW DATABASES ">SHOW DATABASES~</option>
                   <option value="SHOW TABLES ">SHOW TABLES~</option>
                   <option value="SHOW FULL COLUMNS FROM ">SHOW COLUMNS</option>
                   <option value="SHOW INDEX FROM ">SHOW INDEX</option>
                   <option value="SHOW TABLE STATUS ">SHOW TABLE STATUS~</option>
                   <option value="SHOW STATUS ">SHOW STATUS~</option>
                   <option value="SHOW VARIABLES ">SHOW VARIABLES</option>
                   <option value="SHOW LOGS ">SHOW LOGS (BDB - Berkeley DB)</option>
                   <option value="SHOW FULL PROCESSLIST ">SHOW PROCESSLIST</option>
                   <option value="SHOW GRANTS FOR ">SHOW GRANTS FOR username</option>
                   <option value="SHOW CREATE TABLE ">SHOW CREATE TABLE</option>
                   <option value="SHOW MASTER STATUS ">SHOW MASTER STATUS</option>
                   <option value="SHOW MASTER LOGS ">SHOW MASTER LOGS</option>
                   <option value="SHOW SLAVE STATUS ">SHOW SLAVE STATUS</option>
                   <option value="SHOW KEYS FROM ">SHOW KEYS</option>
                   <option value="SHOW FIELDS FROM ">SHOW FIELDS</option>
                   <option value="REPAIR TABLE ">REPAIR TABLE</option>
                   <option value="OPTIMIZE TABLE ">OPTIMIZE TABLE</option>
                   <option value="CHECK TABLE ">CHECK TABLE</option>
                   <option value="SELECT * FROM table_name PROCEDURE ANALYSE() ">SELECT * FROM ... PROCEDURE ANALYSE()~</option>
                   <option value="ANALYZE TABLE ">ANALYZE TABLE</option>
               </OPTGROUP>
               <OPTGROUP label="Non SQL commands">
                   <option value='REPLACE PREFIX `<?php echo $prefix ?>` TO `newprefix_`'>REPLACE PREFIX <?php echo $prefix ?> TO</option>
               </OPTGROUP>
               </select> &nbsp; &nbsp; <?php echo _ES_TABLE;?>
               <SELECT class="text_area" id="easysql_table" NAME="easysql_table" onchange="changeQuery(this);">
               <?php echo $htmTablesList;?>
               </SELECT> &nbsp; &nbsp; <?php echo _ES_DISPLAY_RECORDS;?>
               <INPUT class="text_area" TYPE="text" ID="easysql_records" NAME="easysql_records" VALUE="<?php echo $nDisplayRecords; ?>" SIZE=2 onchange="changeQuery(this)">&nbsp;&nbsp;&nbsp;
       </td>
</tr>
<tr>
       <td>
               <TEXTAREA class="text_area" ID="easysql_query" NAME="easysql_query" style="width:100%;height:150px;"><?php echo $cCurrentSQL;?></TEXTAREA>
               <INPUT TYPE="hidden" NAME="task" VALUE="">
       </td>
</tr>
</table>
<?php echo $htmTableData; ?>
</form>

<?php
}


////////////////////////////////////////////////////////////////
// Make grid table from result query
////////////////////////////////////////////////////////////////
function is_table($table, $database) {
       $tables = $database->getTableList();
       $table = str_replace("#__", $database->_table_prefix, $table);
       return (strpos(implode(";", $tables),$table)>0);
}

////////////////////////////////////////////////////////////////
// Make grid table from result query
////////////////////////////////////////////////////////////////
function Record_HTML($query,$num, $database) {
       // trim long query for output
       $show_query = (strlen(trim($query))>100) ? substr($query,0,50).'...' : $query;
       // exec query
       $database->setQuery( $query );
       $rows = @$database->loadAssocList();
       $aff_rows = $database->getAffectedRows();
       $num++;
       $body = "<br> $num. [ ".$show_query." ], ";
       $body .= 'rows: '.$aff_rows;
       $body .= '<br>';
       $table = TableFromSQL($query); // get table name from query string
       $_sel = (substr(strtolower($query),0,6)=='select' && !strpos(strtolower($query), 'procedure analyse'));
       // If return rows then display table
       if (!empty($rows)) {
               // Begin form and table
               $body .= '<br/><div style="overflow: auto;"><table class="adminlist">';
               $body .= "<thead><tr>\n";
               // Display table header
               if ($_sel)  $body.='<th>Controls</th>';
               $k_arr=$rows[0];
               $f = 1;
               $key = '';
               foreach($k_arr as $var=>$val) {
                       if ($f) {$key = $var;$f=0;}
                       if(ereg("[a-zA-Z]+",$var,$array))
                               $body.='<th>'.$var."</th>\n";
               }
               $body .= "</tr></thead>\n";
               // Get unique field of table
               $uniq_fld = (is_table($table, $database)) ? GetUniqFld($table, $database) : '';
               $key = empty($uniq_fld) ? $key : $uniq_fld;
               // Display table rows
               $k = 0;
               $i=0;
               foreach($rows as $row) {
                       $body .='<tbody><tr valign=top class="row'.$k.'">';
                       if ($_sel)
                               $body .= '<td align=center nowrap><a onclick="this.href=\'index2.php?option=com_easysql&task=edit&hidemainmenu=1&prm1='.base64_encode($table).'&key='.$key.'&id='.$row[$key].'&prm2='.base64_encode($query).'\';" href="#"><img border=0 src="../images/M_images/edit.png" alt="'._ES_EDIT.'" /></a>&nbsp;<a onclick="if (confirm(\'Удалить эту запись ?\')) {this.href=\'index2.php?option=com_easysql&task=delete&prm1='.base64_encode($table).'&key='.$key.'&id='.$row[$key].'&prm2='.base64_encode($query).'\'};" href="#"><img border=0 src="images/publish_x.png" alt="'._ES_DELETE.'" /></a></td>';

                       foreach($row as $var=>$val) {
                               if (ereg("[a-zA-Z]+",$var,$array))
                                       $body .= '<td>&nbsp;'.htmlspecialchars(substr($val,0,100))."&nbsp;</td>\n";
                       }
                       $body .= "</tbody></tr>\n";
                       $k = 1 - $k;
                       $i++;
               }
               // End table and form
               $body .= '</table><br></div>';
               $body .= '<INPUT TYPE="hidden" NAME="key" VALUE="'.$key.'">';
       } else {
               // Display DB errors
               $body .= '<small style="color:red;">'.$database->_errorMsg.'</small><br/>';
       }
       return $body.'<br/>';
}


////////////////////////////////////////////////////////////////
// Get unique field of table
////////////////////////////////////////////////////////////////
function GetUniqFld($table, $database) {

       $database->setQuery( 'SHOW KEYS FROM '.$table );
       $indexes = @$database->loadAssocList();

       $uniq_fld = '';
       if (!empty($indexes))
       foreach($indexes as $index)
               if ($index['Non_unique']==0) {
                       $uniq_fld = $index['Column_name'];
                       break;
               }
       return $uniq_fld;
}

////////////////////////////////////////////////////////////////
// Split multistring query in array
////////////////////////////////////////////////////////////////
function split_sql($sql) {
       $sql = trim($sql);
       $sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);
       $buffer = array();
       $ret = array();
       $in_string = false;
       for($i=0; $i<strlen($sql)-1; $i++) {
               if($sql[$i] == ";" && !$in_string) {
                       $ret[] = substr($sql, 0, $i);
                       $sql = substr($sql, $i + 1);
                       $i = 0;
               }
               if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
                       $in_string = false;
               }
               elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
                       $in_string = $sql[$i];
               }
               if(isset($buffer[1])) {
                       $buffer[0] = $buffer[1];
               }
               $buffer[1] = $sql[$i];
       }
       if (!empty($sql)) {
               $ret[] = $sql;
       }
       return($ret);
}
////////////////////////////////////////////////////////////////
// Get table name from query
////////////////////////////////////////////////////////////////
function TableFromSQL($sql) {
       $in = strpos(strtolower($sql), 'from ')+5;
       $end = strpos($sql, ' ', $in);
       $end = empty($end) ? strlen($sql) : $end;  // If table name in query end
       return substr($sql, $in, $end-$in);
}

////////////////////////////////////////////////////////////////
// Display page for editing record of grid
////////////////////////////////////////////////////////////////
function EditRecord($task, $table, $id, $database) {
       $sql = base64_decode(mosGetParam( $_GET, 'prm2', null ));
       $key = mosGetParam( $_GET, 'key', mosGetParam( $_POST, 'key', null ) );
       if ($task=='edit') {
               $get_fld_value = '$value = $rows[$id][$field];';
       } else {
               $table = mosGetParam( $_POST, 'easysql_table', null );
               $sql = mosGetParam( $_POST, 'easysql_query', null );
               $get_fld_value = '$value = "";';
       }
       if ( !is_null($sql) && !is_null($key) && !is_null($task) ) {
               $fields = $database->getTableFields( array($table) );
               $database->setQuery( $sql );
               $rows = @$database->loadAssocList();
               $last_key_vol = $rows[count($rows)-1][$key];
               if ($task=='edit') {
                       foreach($rows as $row) $rows[$row[$key]] = $row;
               } else {
                       $rows[0] = array();
               }
?>
<form id="adminForm" action="index2.php?option=com_easysql" method="post" name="adminForm">
       <table class="adminheading">
               <tr>
                       <th>
                       <?php echo "$table [ $key = $id ]";?>:
                       <small>
                       <?php echo $task=='edit' ? 'Edit' : 'New';?>
                       </small>
                       </th>
               </tr>
               </table>
               <table class="adminlist">
               <tr>
                       <th colspan="2"><?php echo _ES_DETAILS;?></th>
               </tr>
               <?php $k=0; foreach($fields[$table] as $field=>$type) { ?>
               <tr valign="top" class="row<?php echo $k;?>">
                       <td width="20%" class="key"><?php echo $field;?>: <?php echo $key==$field?"PK":"";?></td>
                       <td width="80%">
                               <?php
                               if (($key==$field) && ($task=='edit')) {
                                       echo $id.GetHtmlForType( $field, 'hidden', $id ).' [ '.$type.' ]';
                               } else {
                                       if (($key==$field) && ($task=='new'))
                                               if (is_numeric($last_key_vol)) $value = $last_key_vol+1; else $value = $last_key_vol.'_1';
                                       else
                                               eval($get_fld_value);
                                       echo GetHtmlForType( $field, $type, $value ).' [ '.$type.' ]';
                               }
                               ?>
                       </td>
               </tr>
               <?php  $k = 1 - $k;}  ?>
       </table>
       <INPUT TYPE="hidden" NAME="task" VALUE="">
       <INPUT TYPE="hidden" NAME="key" VALUE="<?php echo $key;?>">
       <INPUT TYPE="hidden" NAME="easysql_table" VALUE="<?php echo base64_encode($table);?>">
       <INPUT TYPE="hidden" NAME="easysql_query" VALUE="<?php echo base64_encode($sql);?>">
</form>
<?php
       }
}
////////////////////////////////////////////////////////////////
// Save record
////////////////////////////////////////////////////////////////
function SaveRecord($database) {
       $table  = base64_decode(mosGetParam( $_POST, 'easysql_table', null ));
       $key    = mosGetParam( $_POST, 'key', null );
       $sql    = base64_decode(mosGetParam( $_POST, 'easysql_query', null ));
       $fields = mosGetParam( $_POST, 'field', null );
       if ( (!is_null($table)) && !is_null($sql) && !is_null($fields) ) {
               $sql_save = "UPDATE $table SET ";
               $i=0;
               $comma = ', ';
               $cnt = count($fields);
               foreach($fields as $name=>$val) {
                       $i++;
                       if ($cnt<=$i) $comma = '';                      
                       $sql_save .= "`$name`='".htmlspecialchars($val,ENT_QUOTES)."'".$comma;
               }
               $sql_save .= " WHERE `$key`='".$fields[$key]."'";
       }
       //$database->execute( $sql_save );
       $database->setQuery( $sql_save );
       @$database->loadAssocList();
       if (!empty($database->_errorMsg)) {
               echo '<small style="color:red;">'.$database->_errorMsg.'</small><br/>';
               return false;
       } else {
               return true;
       }
}
////////////////////////////////////////////////////////////////
// Create new record
////////////////////////////////////////////////////////////////
function InsertRecord($database) {
       $table  = base64_decode(mosGetParam( $_POST, 'easysql_table', null ));
       $sql    = base64_decode(mosGetParam( $_POST, 'easysql_query', null ));
       $fields = mosGetParam( $_POST, 'field', null );
       if ( (!is_null($table)) && !is_null($sql) && !is_null($fields) ) {
               $i=0;
               $comma = ', ';
               $cnt = count($fields);
               $sql_fields = '';
               $sql_values = '';
               foreach($fields as $name=>$val) {
                       $i++;
                       if ($cnt<=$i) $comma = '';                      
                       $sql_fields .= "`$name`".$comma;
                       $sql_values .= "'$val'".$comma;
               }
               $sql_insert = "INSERT INTO $table ($sql_fields) VALUES($sql_values)";
       }
       //@$database->execute( $sql_save );
       $database->setQuery( $sql_insert );
       @$database->loadAssocList();

       if (!empty($database->_errorMsg)) {
               echo '<small style="color:red;">'.$database->_errorMsg.'</small><br/>';
               return false;
       } else {
               return true;
       }
}

////////////////////////////////////////////////////////////////
// Delete record
////////////////////////////////////////////////////////////////
function DeleteRecord($table, $id, $database) {
       $task = mosGetParam( $_GET, 'task', null );
       $sql = base64_decode(mosGetParam( $_GET, 'prm2', null ));
       $key = mosGetParam( $_GET, 'key', null );
       if ( !is_null($sql) && !is_null($key) && !is_null($task) ) {
               //$database->execute("DELETE FROM $table WHERE $key = '$id'");
               $database->setQuery( "DELETE FROM $table WHERE $key = '$id'" );
               @$database->loadAssocList();
               if (!empty($database->_errorMsg)) {
                       echo '<small style="color:red;">'.$database->_errorMsg.'</small><br/>';
                       break;
                       return false;
               } else {
                       return true;
               }
       }
}


////////////////////////////////////////////////////////////////
// Get html field for table type
////////////////////////////////////////////////////////////////
function GetHtmlForType( $name, $type, $value ) {
       $type = trim(eregi_replace( 'unsigned', '', $type));
       switch (strtolower($type)) {
               //text
               case 'hidden':
                       $ret='<INPUT TYPE="hidden" NAME="field['.$name.']" value="'.$value.'">';
                       break;
               case 'disabled':
                       $ret='<INPUT DISABLED TYPE="text" NAME="field['.$name.']" value="'.$value.'">';
                       break;
               case 'char':
               case 'nchar':
                       $ret='<INPUT TYPE="text" NAME="field['.$name.']"  style="width:7%;" value="'.$value.'">';
                       break;
               case 'varchar':
               case 'nvarchar':
                       $ret='<INPUT TYPE="text" NAME="field['.$name.']" style="width:40%;" value="'.$value.'">';
                       break;
               case 'tinyblob':
               case 'tinytext':
               case 'blob':
               case 'text':
                       $ret='<TEXTAREA NAME="field['.$name.']" style="width:70%;">'.$value.'</TEXTAREA>';
                       break;
               case 'mediumblob':
               case 'mediumtext':
               case 'longblob':
               case 'longtext':
                       $ret='<TEXTAREA NAME="field['.$name.']" style="width:70%;height:150px;">'.$value.'</TEXTAREA>';
                       break;
               //int
               case 'bit':
               case 'bool':
                       $ret='<INPUT TYPE="checkbox" NAME="field['.$name.']">';
                       break;
               case 'tinyint':
               case 'smallint':
               case 'mediumint':
               case 'integer':
               case 'int':
               case 'bigint':
               case 'datetime':
               case 'time':
                       $ret='<INPUT TYPE="text" NAME="field['.$name.']" style="width:15%;" value="'.$value.'">';
                       break;
               //real
               case 'real':
               case 'float':
               case 'decimal':
               case 'numeric':
               case 'double':
               case 'double precesion':
                       $ret='<INPUT TYPE="text" NAME="field['.$name.']" style="width:15%;" value="'.$value.'">';
                       break;
               default:
                       return false;
       }
       return $ret;
}

function replace_prefix( $database, $db, $query ) {
    global $mainframe, $mosConfig_dbprefix, $mosConfig_absolute_path;

    $msg = '';
    //$new_prefix = trim(str_replace(array("'",'"', 'replace joomlaprefix to'), '', trim(strtolower($query))));

    $isJ15 = defined( '_JEXEC' );
    if ($isJ15) {
        $config_fname = JPATH_CONFIGURATION.DS.'configuration.php';
    } else {
        $config_fname = $mosConfig_absolute_path . '/configuration.php';
    }
    list($prefix, $new_prefix) = sscanf(str_replace(array('`', '"', "'"),'',strtolower(trim($query))), "replace prefix %s to %s");

    if (!is_writable($config_fname)) {
        echo '<h2 style="color: red;">'.sprintf(_ES_NO_CONFIG, $config_fname).'</h2>';
        return;
    }

    $database->setQuery( "SHOW TABLES LIKE '".$prefix."%'" );
    $tables = $database->loadResultArray();

    foreach($tables as $tbl) {
        $new_tbl = str_replace($prefix, $new_prefix, $tbl);
	$database->setQuery( 'ALTER TABLE `'.$tbl.'` RENAME `'.$new_tbl.'`' );
        $database->query();
        if (!empty($database->_errorMsg)) {
            echo '<small style="color:red;">'.$database->_errorMsg.'</small><br/>';
        }
    }

    if ($isJ15) {
        $config =& JFactory::getConfig();
        $config->setValue('config.dbprefix', $new_prefix);
        jimport('joomla.filesystem.path');
        if (!$ftp['enabled'] && JPath::isOwner($config_fname) && !JPath::setPermissions($config_fname, '0644')) {
            JError::raiseNotice('SOME_ERROR_CODE', 'Could not make configuration.php writable');
        }
        jimport('joomla.filesystem.file');
        if (JFile::write($config_fname, $config->toString('PHP', 'config', array('class' => 'JConfig')))) {
            $msg = _ES_DONE;
        } else {
            $msg = JText::_('ERRORCONFIGFILE');
        }
        $mainframe->redirect( 'index.php?option=com_easysql', $msg );
    } else {
        require_once($mosConfig_absolute_path.'/administrator/components/com_config/config.class.php');
        $row = new mosConfig();
        $row->bindGlobals();
        $row->config_dbprefix = $new_prefix;
	$config = "<?php \n";
	//$RGEmulation = intval( mosGetParam( $_POST, 'rgemulation', 0 ) );
	$config .= "if(!defined('RG_EMULATION')) { define( 'RG_EMULATION', 0 ); }\n";
	$config .= $row->getVarText();
	$config .= "setlocale (LC_TIME, \$mosConfig_locale);\n";
	$config .= '?>';
	$oldperms = fileperms($config_fname);
	@chmod( $config_fname, $oldperms | 0222);
	if ( $fp = fopen($config_fname, 'w') ) {
		fputs($fp, $config, strlen($config));
		fclose($fp);
		@chmod($fname, $oldperms);
        }
        mosRedirect( 'index2.php?option=com_easysql', _ES_DONE );
    }


}
