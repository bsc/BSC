<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php jimport( 'joomla.html.editor' ); $editor =& JFactory::getEditor(); ?>
<?php jimport( 'joomla.html.html' ); ?>
<?php $data =& $this->data; ?>
<script type="text/javascript">

	function submitbutton(pressbutton)	{
		var form = document.adminForm;
	
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
	
		// remove this code
		alert ('<?php echo 'Remember to add js check in ' . __FILE__ . ' after line n. ' . __LINE__; ?>');
		submitform( pressbutton );
		return;
		// end remove this code
	
		// do field validation
		if (form.My_Field_Name.value == "") {
			alert( "<?php echo JText::_( 'Field must have a name', true ); ?>" );
		} else if (form.My_Field_Name.value.match(/[a-zA-Z0-9]*/) != form.My_Field_Name.value) {
			alert( "<?php echo JText::_( 'Field name contains bad caracters', true ); ?>" );
		} else if (form.My_Field_Name_typefield.options[form.My_Field_Name_typefield.selectedIndex].value == "0") {
			alert( "<?php echo JText::_( 'You must select a field type', true ); ?>" );		
		} else {
			submitform( pressbutton );
		}
	}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
		<table class="admintable">
<!-- jcb code -->
<tr>
	<td width="100" align="right" class="key">
		<label for="name">
			<?php echo JText::_( 'NAME' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="name" id="name" size="32" maxlength="255" value="<?php echo htmlspecialchars($this->data->name, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="address">
			<?php echo JText::_( 'ADDRESS' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="address" id="address" size="32" maxlength="255" value="<?php echo htmlspecialchars($this->data->address, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="reservations">
			<?php echo JText::_( 'RESERVATIONS' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="reservations" id="reservations" size="32" maxlength="31" value="<?php echo htmlspecialchars($this->data->reservations, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="quicktake">
			<?php echo JText::_( 'QUICKTAKE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $editor->display('quicktake', htmlspecialchars($this->data->quicktake, ENT_QUOTES), '550', '300', '60', '20'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="review">
			<?php echo JText::_( 'REVIEW' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $editor->display('review', htmlspecialchars($this->data->review, ENT_QUOTES), '550', '300', '60', '20'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="notes">
			<?php echo JText::_( 'NOTES' ); ?>:
		</label>
	</td>
	<td>
		<?php echo $editor->display('notes', htmlspecialchars($this->data->notes, ENT_QUOTES), '550', '300', '60', '20'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="smoking">
			<?php echo JText::_( 'SMOKING' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="smoking" id="smoking" size="32" maxlength="1" value="<?php echo htmlspecialchars($this->data->smoking, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="credit_cards">
			<?php echo JText::_( 'CREDIT_CARDS' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="credit_cards" id="credit_cards" size="32" maxlength="255" value="<?php echo htmlspecialchars($this->data->credit_cards, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="cuisine">
			<?php echo JText::_( 'CUISINE' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="cuisine" id="cuisine" size="32" maxlength="31" value="<?php echo htmlspecialchars($this->data->cuisine, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="avg_dinner_price">
			<?php echo JText::_( 'AVG_DINNER_PRICE' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="avg_dinner_price" id="avg_dinner_price" size="32" maxlength="3" value="<?php echo htmlspecialchars($this->data->avg_dinner_price, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="review_date">
			<?php echo JText::_( 'REVIEW_DATE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo JHTML::calendar($this->data->review_date, 'review_date', 'review_date'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="published">
			<?php echo JText::_( 'PUBLISHED' ); ?>:
		</label>
	</td>
	<td>
		<?php echo JHTML::_('select.booleanlist', 'published', null, $this->data->published, JText::_( 'JYES' ), JText::_( 'JNO' ), false); ?>
	</td>
</tr>
<!-- jcb code -->

		</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_nreview" />
<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="reviews" />
</form>
