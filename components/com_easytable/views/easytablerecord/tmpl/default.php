<?php defined('_JEXEC') or die ('Restricted Access'); ?>
<div class="contentpaneopen<?php echo $this->pageclass_sfx ?>" id="etrecord">
	<h2 class="contentheading<?php echo $this->pageclass_sfx ?>"><a href="<?php echo $this->backlink; ?>"><?php echo htmlspecialchars($this->easytable->easytablename); ?></a></h2>
	<p class="et_description"><?php echo htmlspecialchars($this->easytable->description); ?></p>
	<br />
	<div id="easytable-record" class="<?php echo htmlspecialchars($this->easytable->easytablealias); ?> ">
		<table  id="<?php echo htmlspecialchars($this->easytable->easytablealias); ?>" summary="<?php echo htmlspecialchars($this->easytable->description); ?>">
			<thead>
				<tr>
				</tr>
			</thead>
			<tbody>
				<?php
					$fieldNumber = 1; // so that we skip the record id from the table record
					$record = $this->easytables_table_record;

					foreach ($this->easytables_table_meta as $heading )
						{// label, fieldalias, type, detail_link, description, id, detail_view, list_view

							if($heading[6]) // ie. Detail_view = 1
							{
								$f = $record[$fieldNumber++];

								$cellType     = (int)$heading[2];

								switch ($cellType) {
									case 0: // text
										$cellData = $f;
										break;
									case 1: // image
										if($f){
											$pathToImage = $this->imageDir.DS.$f;  // we concatenate the image URL with the tables default image path
											$cellData = '<img src="'.$pathToImage.'" >';
										} else
										{
											$cellData = '<!-- '.JText::_( 'NO_IMAGE_NAME' ).' -->';
										}
										break;
									case 2: // url //For fully qualified URL's starting with HTTP we open in a new window, for everything else its the same window.
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
                                        $cellData = '<a href="mailto:'.trim($f).'" >'.trim($f).'</a>';
                                        break;
										
									default: // oh oh we messed up
										$cellData = "<!-- Field Type Error: cellData = $f / cellType = $cellType -->";
									}

							echo '<tr>';  // Open the row
							$titleString = ''; // Setup the titleString if required
							if(strlen($heading[4])){ $titleString = 'title="'.htmlspecialchars($heading[4]).'" ';}

							echo '<td class="sectiontableheader '.$heading[1].'" '.$titleString.'>'.$heading[0].'</td>'; // Field Heading
							echo '<td class="sectiontablerow '.$heading[1].'">'.$cellData.'</td>'; // Field Data
							echo '</tr>';  // Close the Row
							}
						}
				?>
			</tbody>
		</table>
	<?php
		if( $this->linked_table && $this->tableHasRecords )
		{
			echo('<div id="easytable-linkedtable" class="'.htmlspecialchars($this->easytable->easytablealias).'">');
			echo( $this->loadTemplate('linkedtable') );
			echo('</div>');
		}
	?>
	</div>
</div>
