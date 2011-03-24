<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php jimport( 'joomla.html.editor' ); $editor =& JFactory::getEditor(); ?>
<?php jimport( 'joomla.html.html' ); ?>
<?php $data =& $this->data; ?>
<script type="text/javascript">

	Joomla.submitbutton = function (pressbutton){
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
		<label for="last_name">
			<?php echo JText::_( 'LAST_NAME' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="last_name" id="last_name" size="32" maxlength="255" value="<?php echo htmlspecialchars($this->data->last_name, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="username">
			<?php echo JText::_( 'USERNAME' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="username" id="username" size="32" maxlength="150" value="<?php echo htmlspecialchars($this->data->username, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="email">
			<?php echo JText::_( 'EMAIL' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="email" id="email" size="32" maxlength="100" value="<?php echo htmlspecialchars($this->data->email, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="password">
			<?php echo JText::_( 'PASSWORD' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="password" id="password" size="32" maxlength="100" value="<?php echo htmlspecialchars($this->data->password, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="usertype">
			<?php echo JText::_( 'USERTYPE' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="usertype" id="usertype" size="32" maxlength="25" value="<?php echo htmlspecialchars($this->data->usertype, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="block">
			<?php echo JText::_( 'BLOCK' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="block" id="block" size="32" maxlength="4" value="<?php echo htmlspecialchars($this->data->block, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="sendEmail">
			<?php echo JText::_( 'SENDEMAIL' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="sendEmail" id="sendEmail" size="32" maxlength="4" value="<?php echo htmlspecialchars($this->data->sendEmail, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="gid">
			<?php echo JText::_( 'GID' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="gid" id="gid" size="32" maxlength="3" value="<?php echo htmlspecialchars($this->data->gid, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="registerDate">
			<?php echo JText::_( 'REGISTERDATE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo JHTML::calendar($this->data->registerDate, 'registerDate', 'registerDate'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="lastvisitDate">
			<?php echo JText::_( 'LASTVISITDATE' ); ?>:
		</label>
	</td>
	<td>
		<?php echo JHTML::calendar($this->data->lastvisitDate, 'lastvisitDate', 'lastvisitDate'); ?>
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="activation">
			<?php echo JText::_( 'ACTIVATION' ); ?>:
		</label>
	</td>
	<td>
		<input class="text_area" type="text" name="activation" id="activation" size="32" maxlength="100" value="<?php echo htmlspecialchars($this->data->activation, ENT_COMPAT, 'UTF-8');?>" />
	</td>
</tr>
<tr>
	<td width="100" align="right" class="key">
		<label for="params">
			<?php echo JText::_( 'PARAMS' ); ?>:
		</label>
	</td>
	<td>
		<textarea class="text_area" name="params" id="params" cols="80" rows="10"><?php echo htmlspecialchars($this->data->params, ENT_COMPAT, 'UTF-8');?></textarea>
	</td>
</tr>
<!-- jcb code -->

		</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_mcm" />
<input type="hidden" name="id" value="<?php echo $this->data->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="users" />
</form>
