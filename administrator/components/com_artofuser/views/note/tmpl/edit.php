<?php
/**
 * @version		$Id: edit.php 537 2011-01-15 02:22:57Z eddieajau $
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
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=note&id='.(int) $this->item->id);?>" method="post" name="adminForm" id="category-form">
	<fieldset>
		<legend>
			<?php echo $this->item->id ? JText::sprintf('COM_ARTOFUSER_EDIT_NOTE_N', $this->item->id) : JText::_('COM_ARTOFUSER_NEW_NOTE');?>
		</legend>

		<div class="col width-100">
			<table class="admintable">
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['subject']->label; ?>
					</td>
					<td>
						<?php echo $fields['subject']->input; ?>
					</td>
				</tr>

				<tr>
					<td scope="row" class="key">
						<?php echo $fields['user_id']->label; ?>
					</td>
					<td>
						<?php echo $fields['user_id']->input; ?>
					</td>
				</tr>

				<tr>
					<td scope="row" class="key">
						<?php echo $fields['catid']->label; ?>
					</td>
					<td>
						<?php echo $fields['catid']->input; ?>
					</td>
				</tr>

				<tr>
					<td scope="row" class="key">
						<?php echo $fields['published']->label; ?>
					</td>
					<td>
						<?php echo $fields['published']->input; ?>
					</td>
				</tr>

				<tr>
					<td scope="row" class="key">
						<?php echo $fields['review_time']->label; ?>
					</td>
					<td>
						<?php echo $fields['review_time']->input; ?>
					</td>
				</tr>
			</table>
		</div>

		<div class="clr"></div>
		<?php echo $fields['body']->label; ?>
		<?php echo $fields['body']->input; ?>

		<div class="clr"></div>

		<?php echo $fields['id']->input; ?>
		<input type="hidden" name="task" value="" />
	</fieldset>
	<?php echo JHTML::_('form.token'); ?>
</form>
