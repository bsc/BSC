<?php
/**
 * @version		$Id: default.php 544 2011-01-15 04:40:24Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2011 New Life in IT Pty Ltd. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');

JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');

$fields = $this->form->getFields();
?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(task)
	{
		var form = document.adminForm;
		if (task == 'note.cancel' || document.formvalidator.isValid(form)) {
			submitform(task);
		}
	}
-->
</script>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=block');?>" method="post" name="adminForm" id="block-form">
	<fieldset>
		<legend>
			<?php echo JText::_('COM_ARTOFUSER_BLOCK_USERS'); ?>
		</legend>
		<div class="col width-45" style="margin-right: 20px;">
			<table class="adminlist">
				<tbody>
				<?php foreach ($this->users as $i => $user) : ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td width="20">
							<input type="checkbox" name="cid[]" value="<?php echo (int) $user->id; ?>" checked="checked" />
						</td>
						<td>
							<?php echo $this->escape($user->name); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="col width-45">
			<div>
				<?php echo $fields['block_catid']->label; ?>
				<br /><?php echo $fields['block_catid']->input; ?>
			</div>
			<div>
				<?php echo $fields['note']->label; ?>
				<br /><?php echo $fields['note']->input; ?>
			</div>
			<div class="clr"></div>
			<div>
				<?php echo $fields['review_time']->label; ?>
				<br /><?php echo $fields['review_time']->input; ?>
			</div>

			<?php foreach ((array) $this->state->get('list.user_ids') as $userId) : ?>
			<input type="hidden" name="user_ids[]" value="<?php echo (int) $userId; ?>" />
			<?php endforeach; ?>
			<input type="hidden" name="task" value="" />
			<?php echo JHTML::_('form.token'); ?>
		</div>
	</fieldset>
</form>
