<?php
defined('_JEXEC') or die;

JHtml::_('behavior.mootools');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="registration<?php echo $this->params->get('pageclass_sfx')?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h2 class="componentheading"><?php echo $this->escape($this->params->get('page_heading')); ?></h2>
<?php endif; ?>

	<form id="member-registration" class="user" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($fieldset->name);?>
	<?php if (count($fields)):?>
		<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
		<div class="<?php echo $field->id ?>">
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else: ?>
				<?php echo $field->label; ?>
				<?php if (!$field->required): ?>
					<span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
				<?php endif; ?>
				<?php echo str_replace('class="', 'class="inputbox ', $field->input);?>
			<?php endif;?>
		</div>
		<?php endforeach;?>
	<?php endif;?>
<?php endforeach;?>

		<button type="submit" class="validate"><?php echo JText::_('JREGISTER');?></button>
	<?php echo JText::_('COM_USERS_OR');?>
		<a href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
		<div>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="registration.register" />
			<?php echo JHtml::_('form.token');?>
		</div>
	</form>
</div>
