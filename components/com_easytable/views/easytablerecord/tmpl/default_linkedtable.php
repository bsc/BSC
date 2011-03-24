<?php defined('_JEXEC') or die ('Restricted Access'); ?>
<div class="contentpaneopen<?php echo $this->pageclass_sfx ?>" >
	<table  id="<?php echo htmlspecialchars($this->linked_easytable_alias); ?>" summary="<?php echo htmlspecialchars($this->linked_easytable_description); ?>" width="100%">
		<thead>
			<tr>
				<?php
					$n = 0;
					foreach ($this->linked_field_labels as $heading )
						{
							if($n)
							{
								echo '<td class="sectiontableheader '.$this->linked_fields_alias[$n].'">'.$heading.'</td>';
							}
							$n++;
						}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($this->linked_records as $prow )  // looping through the rows of data
				{
                    $rowId = $prow["id"];
					echo '<tr>';  // Open the row
					$rowNumber = 1; //skip the id of the records
					foreach($prow as $k => $f)  // looping through the fields of the row
					{
						if(!($k == 'id')){				// we skip the row id which is in position 0
							$cellData = '';				// make sure cellData is empty before we start this cell.
							$cellAlias    = $this->linked_fields_alias[$rowNumber];
							$cellType     = (int)$this->linked_field_types[$rowNumber];
                            $cellDetailLink = (int)$this->linked_field_links_to_detail[$rowNumber++];
							switch ($cellType) {
								case 0: // text
									$cellData = trim($f);
									break;
								case 1: // image
									if($f){
										$pathToImage = $this->linked_table_imageDir.DS.$f;  // we concatenate the image URL with the tables default image path
										$cellData = '<img src="'.trim($pathToImage).'" >';
									} else
									{
										$cellData = '<!-- '.JText::_( 'NO_IMAGE_NAME' ).' -->';
									}
									break;
								case 2: // url
									$URLTarget = 'target="_blank"';
									if(substr($f,0,7)!='http://') {$URLTarget = '';}
									if(substr($f,0,8)=='<a href=')
									{
										$cellData = $f;
									}
									else
									{
										$cellData = '<a href="'.trim($f).'" '.$URLTarget.'>'.trim($f).'</a>';
									}
									break;
                                case 3: // mailto
                                    $cellData = '<a href="mailto:'.trim($f).'">'.trim($f).'</a>';
                                    break;
									
								default: // oh oh we messed up
									$cellData = "<!-- Field Type Error: cellData = $cellData / cellType = $cellType / cellDetailLink = $cellDetailLink -->";
								}
                            if($cellDetailLink && ($cellType != 2)) // As a precaution we make sure the detail link cell is not a URL field
                            {
                                $linkToDetail = JRoute::_('index.php?option=com_easytable&view=easytablerecord&id='.$this->linked_table.':'.$this->linked_easytable_alias.'&rid='.$rowId);
                                $cellData = '<a href="'.$linkToDetail.'">'.$cellData.'</a>';
                                $cellDetailLink ='';
                            }
							// Finally we can echo the cell string.
							echo "<td class='colfld ".$cellAlias."'>".trim($cellData).'</td>';
						}
						// End of row stuff should follow after this.
					}
					echo '</tr>';  // Close the Row
					$k = '';
					$rowId = '';   // Clear the rowId to prevent any issues.
				}
			?>
		</tbody>
	</table>
</div>
