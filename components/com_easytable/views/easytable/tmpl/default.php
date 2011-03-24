<?php defined('_JEXEC') or die ('Restricted Access'); ?>
<?php
	echo '<div class="contentpaneopen'.$this->pageclass_sfx.'" >';

    if($this->show_page_title) {
        echo '<h2 class="contentheading'.$this->pageclass_sfx.'">'.htmlspecialchars($this->page_title).'</h2>';
    }
?>
<?php echo ($this->show_created_date ? '<p class="createdate">'.htmlspecialchars($this->easytable->created_).'</p>' : '') ?>
<?php echo ($this->show_modified_date ? '<p class="modifydate">'.$this->modification_date_label.' '.htmlspecialchars($this->easytable->modified_).'</p>' : '') ?>
<?php echo ($this->show_description ? '<p class="et_description">'.htmlspecialchars($this->easytable->description).'</p>' : '') ?>
<br />
<div id="easytable-<?php echo htmlspecialchars($this->easytable->easytablealias); ?>">
	<form name="adminForm" method="post" action="<?php echo $this->paginationLink ?>">
<?php if ($this->show_with_pagination) { ?>
	<div class="et_search_pagination">
<?php } ?>
		<?php
			if( $this->show_search && $this->etmCount) // If search is enabled for this table, show the search box.
			{
				echo JText::_( 'SEARCH' ).': <input type="text" name="etsearch" value="'.$this->search.'" id="etsearch" > <button type="submit">'.JText::_( 'GO' ).'</button>';
			}
			if($this->show_pagination_header)
			{
				if( $this->show_with_pagination && $this->etmCount) // If pagination is enabled show the controls
				{
					echo $this->pagination->getPagesLinks();
				}
				if( $this->show_with_pagination && $this->etmCount) 						// Only if pagination is enabled
				{
					$pofp = $this->pagination->getPagesCounter( );
					if(isset( $pofp )) {
						$pofp = '( '.$pofp.' )';
					}
					$pcntr = $this->pagination->limit;
					if( isset( $pcntr )) {																	 // AND if there's more than one page then show the page display.
						echo JText::_('DISPLAY').': '.$this->pagination->getLimitBox().$pofp;
					}
				}
			}
		?>
<?php if ($this->show_with_pagination) { ?>
	</div>
<?php } ?>
	<table  id="<?php echo htmlspecialchars($this->easytable->easytablealias); ?>" summary="<?php echo htmlspecialchars($this->easytable->description); ?>" width="100%">
		<thead>
			<tr>
				<?php foreach ($this->easytables_table_meta as $heading )
						{
							$titleString = '';
							if(strlen($heading[4])){ $titleString = 'title="'.htmlspecialchars($heading[4]).'" ';}
							echo '<td class="sectiontableheader '.$heading[1].'" '.$titleString.'>'.$heading[0].'</td>';
						}
				?>
			</tr>
		</thead>
		<tbody>
			<?php
				$alt_rv = 0;
				foreach ($this->paginatedRecords as $prow )  // looping through the rows of paginated data
				{
					if($this->pagination->total == $prow->id)
					{
						echo "<tr class='row$alt_rv et_last_row' >";  // Open the LAST row
					}
					else
					{
						echo "<tr class='row$alt_rv' >";  // Open the row
					}
					$labelNumber = 0;
					foreach($prow as $k => $f)  // looping through the fields of the row
					{
						if(!($k == 'id')){				// we skip the row id which is in position 0
							$cellData = '';				// make sure cellData is empty before we start this cell.
							$cellClass    = $this->easytables_table_meta[$labelNumber][1];
							$cellType     = (int)$this->easytables_table_meta[$labelNumber][2];
							$cellDetailLink = (int)$this->easytables_table_meta[$labelNumber++][3];
							switch ($cellType) {
								case 0: // text
									$cellData = trim($f);
									break;
								case 1: // image
									if($f){
										$pathToImage = $this->imageDir.DS.$f;  // we concatenate the image URL with the tables default image path
										$cellData = '<img src="'.trim($pathToImage).'" >';
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
									$cellData = "<!-- Field Type Error: cellData = $cellData / cellType = $cellType / cellDetailLink = $cellDetailLink -->";
								}
							if($cellDetailLink && ($cellType != 2)) // As a precaution we make sure the detail link cell is not a URL field
							{
								$linkToDetail = JRoute::_('index.php?option=com_easytable&view=easytablerecord&id='.$this->tableId.':'.$this->easytable->easytablealias.'&rid='.$rowId);
								$cellData = '<a href="'.$linkToDetail.'">'.$cellData.'</a>';
								$cellDetailLink ='';
							}
							// Finally we can echo the cell string.
							echo "<td class='colfld ".$cellClass."'>".trim($cellData).'</td>';
						}
						else // we store the rowID for possible use in a detaillink
						{
							$rowId = (int)$f;
							//echo '<BR />'.$k.' == '.$f;
						}
						// End of row stuff should follow after this.
					}
					echo '</tr>';  // Close the Row
					$alt_rv = (int)!$alt_rv;
					$k = '';
					$rowId = '';   // Clear the rowId to prevent any issues.
				}
			?>
		</tbody>
	</table>
	<input type="hidden" value="0" name="limitstart"/>
<?php
	if( $this->show_pagination_footer && $this->etmCount) // If pagination is enabled show the controls
	{
		echo '<div class="pagination_footer">';
		echo $this->pagination->getListFooter();
		echo '</div>';
	}
?>
	</form>
</div>
</div> <!-- contentpaneclosed -->
