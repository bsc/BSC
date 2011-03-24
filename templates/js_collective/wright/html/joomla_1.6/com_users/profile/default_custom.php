<?php
defined('_JEXEC') or die;
?>
<?php $fields = $this->form->getFieldset('profile');?>
<?php if (count($fields)):;?>
<fieldset id="users-profile-custom">
	<legend><?php echo JText::_('COM_USERS_PROFILE_CUSTOM_LEGEND'); ?></legend>
	<ul>
	<?php foreach($fields as $field):?>
		<?php if (!$field->hidden) :?>
		<li><?php echo $field->label; ?>
		<?php echo !empty($this->data->profile[$field->fieldname]) ? $this->data->profile[$field->fieldname] : JText::_('COM_USERS_PROFILE_VALUE_NOT_FOUND'); ?>
		</li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
</fieldset>
<?php endif;?>
