<?php
/**
 * @package    EasyTables
 * @author     Craig Phillips {@link http://www.seepeoplesoftware.com}
 * @author     Created on 13-Jul-2009
 */

//--No direct access
defined('_JEXEC') or die('Restricted Access');
	JHTML::_('behavior.tooltip');
		if($this->row->id)
		{
			JToolBarHelper::title(JText::_( 'EDIT_TABLE' ), 'addedit.png');
		}
		else
		{
			JToolBarHelper::title(JText::_( 'ADD_TABLE' ), 'addedit.png');
		}
		
		JToolBarHelper::save();
		JToolBarHelper::apply();
		
		if($this->row->id)
		{
			JToolBarHelper::cancel('cancel', JText::_( 'Close' ));
		}
		else
		{
			JToolBarHelper::cancel();
		}
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col100">
		<table width="100%">
			<tr>
				<td>
				<fieldset class="adminform">
				<legend>Details</legend>
				<table class="admintable">
					<tr>
						<td width="100" align="right" class="key">
							<label for="easytablename">
								<?php echo JText::_( 'TABLE' ); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="easytablename" id="easytablename" size="32" maxlength="250" value="<?php echo $this->row->easytablename;?>" />			</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<label for="easytablealias">
								<?php echo JText::_( 'ALIAS' ); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="easytablealias" id="easytablealias" size="32" maxlength="250" value="<?php echo $this->row->easytablealias;?>" />			</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<label for="description">
								<?php echo JText::_( 'DESCRIPTION' ); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo $this->row->description;?>" />
						</td>
					</tr>
			   		<tr>
						<td width="100" align="right" class="key">
							<label for="defaultimagedir" title="The default location of images used with this table.">
								<?php echo JText::_( 'IMAGE_DIRECTORY' ); ?>:
							</label>
						</td>
						<td>
							<input class="text_area" type="text" name="defaultimagedir" id="defaultimagedir" size="32" maxlength="250" value="<?php echo $this->row->defaultimagedir;?>" />
			            	<?php if(! $this->row->defaultimagedir ) { ?>
			            		<span class="et_nodirectory" style="font-style:italic;color:red;"><?php echo JText::_( 'NO_DIRECTORY_SET' ); ?></span>
			                <?php } ?>
						</td>
					</tr>
			   		<tr>
			   			<?php
			   			$pubTitle = JText::_('THE___PUBLISHED___STATUS_OF_THIS_TABLE_');
			   			if(!$this->ettd)
				   			{
				   				$pubTitle .= JText::_( 'A_TABLE_CAN__T_BE_PUBLISHED_WITHOUT_DATA_BEING_ADDED_' );
				   			}
			   			?>
						<td width="100" align="right" class="key">
							<label for="published" title="<?php echo $pubTitle ?>">
								<?php echo JText::_( 'PUBLISHED' ); ?>:
							</label>
						</td>
						<td>
							<?php echo $this->published;?>
						</td>
					</tr>
			        <tr>
						<td width="100" align="right" class="key">
							<label for="tableimport">
							<?php
								if($this->ettd) {
									echo JText::_( 'SELECT_AN_UPDATE_FILE' ); 
								} else
								{
									echo JText::_( 'SELECT_A_NEW_CSV_FILE' );
								}
							?>:
							</label>
						</td>
			        	<td>
							<!-- MAX_FILE_SIZE must precede the file input field -->
							<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
							<!-- Name of input element determines name in $_FILES array -->
							<input name="tablefile" type="file" />
							<?php
								if($this->ettd) {
									echo '<input type="button" value="'.JText::_( 'UPLOAD_FILE' ).'" onclick="javascript: submitbutton(\'updateETDTable\')" /><br />';
								}
								else
								{
									echo '<input type="button" value="'.JText::_( 'UPLOAD_FILE' ).'" onclick="javascript: submitbutton(\'createETDTable\')" /><br />';
								}
							?>
							<?php echo JText::_( 'FIRST_LINE_OF_CSV_FILE_CONTAINS_COLUMN_HEADINGS_' ).' '.$this->CSVFileHasHeaders; ?>
						</td>
					</tr>
				</table>
				</fieldset>
				</td>
				<td width="320" valign="top" style="padding: 7px 0pt 0pt 5px;">
					<table width="100%" style="border: 1px dashed silver; padding: 5px; margin-bottom: 10px;">
						<tbody>
							<tr>
								<td><strong><?php echo JText::_( 'TABLE_ID' ); ?>:</strong></td>
								<td><?php echo $this->row->id; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo JText::_( 'STATE' ); ?>:<br /></strong></td>
								<td><?php echo $this->state; ?></td>
							</tr>
							<tr>
								<td
								 valign="top"
								 title="EasyTable adds a field for it's primary key, so the field count will be 1 more than the fields you have access to.">
									<strong><?php echo JText::_( 'STRUCTURE' ); ?>:</strong>
								</td>
								<td>
									<?php
										echo $this->ettm_field_count.' '.JText::_('FIELDS').'<br />';
										echo JText::_('TABLE__').$this->ettd_tname.' '.'<br />';
										if($this->ettd)
										{
											echo $this->ettd_tname.' '.JText::_('HAS').' '.$this->ettd_record_count.' '.JText::_('RECORDS_');
										}
										else
										{
											echo '<span style="font-style:italic;color:red;">'.JText::_( 'NO_DATA_TABLE_FOUND_FOR_' ).$this->ettd_tname.'! </span>';
										}
									?>
								</td>
							</tr>
							<tr>
								<td><br /><strong><?php echo JText::_( 'CREATED' ); ?>:</strong></td>
								<td><br /><?php echo $this->createddate;?></td>
							</tr>
							<tr>
								<td><strong><?php echo JText::_( 'MODIFIED' ); ?>:</strong></td>
								<td><?php echo $this->modifieddate;?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php if($this->ettd) { ?>
			<tr>
				<td title="Meta data for fields in Table <?php echo $this->row->easytablename.' ('.$this->ettd_tname.')'; ?>!">
					<fieldset class="adminform">
						<legend><?php echo $this->row->easytablename.' '.JText::_( 'FIELD_CONFIGURATION' ); ?></legend>
						<table class="adminlist">
						<thead>
							<tr valign="top">
								<th><?php echo JText::_( 'ID' ); ?></th>
								<th><?php echo JText::_( 'POSITION' ); ?></th>
								<th><?php echo JText::_( 'LABEL__ALIAS_' ); ?></th>
								<th><?php echo JText::_( 'DESCRIPTION' ); ?></th>
								<th><?php echo JText::_( 'TYPE' ); ?></th>
								<th><?php echo JText::_( 'LIST_VIEW' ); ?></th>
								<th><?php echo JText::_( 'DETAIL_LINK' ); ?></th>
								<th><?php echo JText::_( 'DETAIL_VIEW' ); ?></th>
							</tr>
						</thead>
						<?php
							$mRIds = array();
							$k = 0;
							foreach ($this->easytables_table_meta as $metaRow)
							{
								$mRId = $metaRow[0];
								$mRIds[] = $mRId;
								echo '<tr valign="top" class="row'.$k.'">
								';																		// Open the row
								
								echo('<td align="center"><input type="hidden" name="id'.$mRId.'" value="'.$mRId.'">'.$mRId.'</td>');				// Id
								echo('<td align="center"><input type="text" value="'.$metaRow[2].'" size="3" name="position'.$mRId.'"></td>');		// Position
								echo('<td><input type="text" value="'.$metaRow[3].'" name="label'.$mRId.'"><br>'.									// label <br />
									'<em><input type="hidden" name="fieldalias'.$mRId.'" value="'.$metaRow[9].'">'.$metaRow[9].'</em></td>');		// alias
								echo('<td><textarea cols="50" rows="2" name="description'.$mRId.'">'.$metaRow[4].'</textarea></td>');				// Description
								echo('<td>'.$this->getTypeList($metaRow[0], $metaRow[5]).'</td>');													// Type
								
								$tdName			= 'list_view'.$mRId;
								$tdStart		= '<td align="center"><input type="hidden" name="'.$tdName.'" value="'.$metaRow[6].'">';			// List View Flag
								$tdEnd			= '</td>';
								$tdFlagImg		= $this->getListViewImage($tdName, $metaRow[6]);
								$tdjs			= 'toggleTick(\'list_view\', '.$mRId.');';
								$tdFlagImgLink	= '<a href="javascript:void(0);" onclick="'.$tdjs.'">'.$tdFlagImg.'</a>';
								echo($tdStart.$tdFlagImgLink.$tdEnd);
								
								$tdName			= 'detail_link'.$mRId;
								$tdStart       = '<td align="center"><input type="hidden" name="'.$tdName.'" value="'.$metaRow[7].'">';				// Detail Link Flag
								$tdFlagImg     = $this->getListViewImage($tdName, $metaRow[7]);
								$tdjs			= 'toggleTick(\'detail_link\', '.$mRId.');';
								$tdFlagImgLink	= '<a href="javascript:void(0);" onclick="'.$tdjs.'">'.$tdFlagImg.'</a>';
								echo($tdStart.$tdFlagImgLink.$tdEnd);
								
								$tdName			= 'detail_view'.$mRId;
								$tdStart       = '<td align="center"><input type="hidden" name="'.$tdName.'" value="'.$metaRow[8].'">';				// Detail View Flag
								$tdFlagImg     = $this->getListViewImage($tdName, $metaRow[8]);
								$tdjs			= 'toggleTick(\'detail_view\', '.$mRId.');';
								$tdFlagImgLink	= '<a href="javascript:void(0);" onclick="'.$tdjs.'">'.$tdFlagImg.'</a>';
								echo($tdStart.$tdFlagImgLink.$tdEnd);
								
								
								echo '</tr>';                                                                                                        // Close the row
								$k = 1 - $k;
							}
							echo('<tr><td><input type="hidden" name="mRIds" value="'.implode(', ',$mRIds).'"></td></tr>')
						?>
						</table>
					</fieldset>
				</td>
				<td valign="top">
					<fieldset class="adminform">
					<legend><?php echo( JText::_( 'PARAMETERS' ) ); ?></legend>
					<?php
						jimport('joomla.html.pane');

						$pane =& JPane::getInstance( 'sliders' );
						 
						echo $pane->startPane( 'content-pane' );
						 
						// First slider panel
						// Create a slider panel with a title of 'Linked Table Settings' and a title id attribute of LINKED_TABLE
						echo $pane->startPanel( JText::_( 'LINKED_TABLE_SETTINGS' ), 'LINKED_TABLE' );
						// Display the parameters defined in the <params> group with the 'group' attribute of 'GROUP_NAME'
						echo $this->params->render( 'params', 'LINKED_TABLE' );
						echo $pane->endPanel();
						
						//Second slider panel
						// Create a slider panel with a title of 'Table Preferences' and a title id attribute of Table_Preferences
						echo $pane->startPanel( JText::_( 'TABLE_PREFERENCES' ), 'EASYTABLE_PREFS' );
						// Display the parameters defined in the <params> group with group nambe EASYTABLE_PREFS attribute
						echo $this->params->render( 'params', 'EASYTABLE_PREFS' );
						echo $pane->endPanel();
						 
						echo $pane->endPane();
					?>
					</fieldset>
				</td>
			</tr>
			<?php } ?>
		</table>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_('form.token'); ?>
<!-- <input type="hidden" name="controller" value="easytable" /> -->
</form>
