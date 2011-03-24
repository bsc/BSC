<?php

defined('_JEXEC') or die;

jimport('joomla.user.helper');
?>

<fieldset id="users-profile-core">
	<legend>
		<?php echo JText::_('COM_USERS_PROFILE_CORE_LEGEND'); ?>
	</legend>
	<ul class="user">
		<li>
			<?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?>
			<?php echo $this->data->name; ?>
		</li>
		<li>
			<?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL'); ?>
			<?php echo $this->data->username; ?>
		</li>
		<li>
			<?php echo JText::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL'); ?>
			<?php echo JHTML::_('date',$this->data->registerDate); ?>
		</li>
		<li>
			<?php echo JText::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL'); ?>
		<?php if ($this->data->lastvisitDate != '0000-00-00 00:00:00'){?>
				<?php echo JHTML::_('date',$this->data->lastvisitDate); ?>
		<?php }
		else {?>
				<?php echo JText::_('COM_USERS_PROFILE_NEVER_VISITED'); ?>
		<?php } ?>
		</li>
	</ul>
</fieldset>
