<?php

defined('_JEXEC') or die;

	$script = '<!--
		function validateForm(frm) {
			var valid = document.formvalidator.isValid(frm);
			if (valid == false) {
				// do field validation
				if (frm.email.invalid) {
					alert("' . JText::_('COM_CONTACT_CONTACT_ENTER_VALID_EMAIL', true) . '");
				} else if (frm.text.invalid) {
					alert("' . JText::_('COM_CONTACT_FORM_NC', true) . '");
				}
				return false;
			} else {
				frm.submit();
			}
		}
		// -->';
	$document = JFactory::getDocument();
	$document->addScriptDeclaration($script); ?>

<?php if(isset($this->error)) : ?>
<tr>
	<td><?php echo $this->error; ?></td>
</tr>
<?php endif; ?>


<tr>
	<td colspan="2">
	<br /><br />
	<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="emailForm" id="emailForm" class="form-validate">
		<div class="contact_email<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
			<div class="jform-required">
			<?php echo JText::_('COM_CONTACT_CONTACT_REQUIRED');?>
			</div>
			<label for="contact_name">
				&nbsp;<?php echo JText::_( 'COM_CONTACT_CONTACT_EMAIL_NAME' );?>:
			</label>
			<br />
			<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
			<br />
			<label id="contact_emailmsg" for="contact_email">
				&nbsp;<?php echo JText::_( 'JGLOBAL_EMAIL' );?>:
			</label>
			<br />
			<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
			<br />
			<label for="contact_subject">
				&nbsp;<?php echo JText::_( 'COM_CONTACT_CONTACT_MESSAGE_SUBJECT' );?>:
			</label>
			<br />
			<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
			<br /><br />
			<label id="contact_textmsg" for="contact_text">
				&nbsp;<?php echo JText::_( 'COM_CONTACT_CONTACT_ENTER_MESSAGE' );?>:
			</label>
			<br />
			<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
			<?php if ($this->contact->params->get( 'show_email_copy' )) : ?>
			<br />
				<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
				<label for="contact_email_copy">
					<?php echo JText::_( 'COM_CONTACT_CONTACT_EMAIL_A_COPY' ); ?>
				</label>
			<?php endif; ?>
			<br />
			<br />
			<button class="button validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>

			<input type="hidden" name="option" value="com_contact" />
			<input type="hidden" name="view" value="contact" />
			<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
			<input type="hidden" name="task" value="submit" />
			<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
	<br />
	</td>
</tr>