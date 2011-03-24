<?php
/**
 * @version		$Id: edit.php 537 2011-01-15 02:22:57Z eddieajau $
 * @package		NewLifeInIT
 * @subpackage	com_artofuser
 * @copyright	Copyright 2005 - 2011 New Life in IT Pty Ltd. All rights reserved
 * @license		GNU General Public License <http://www.fsf.org/licensing/licenses/gpl.html>
 * @link		http://www.theartofjoomla.com
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::stylesheet('default.css', 'administrator/components/com_artofuser/media/css/');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');

$fields = $this->form->getFields();

// Allow the layout to add custom fieldsets.
$customFieldsets = $this->getCustomFieldSets();
?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(task)
	{
		var form = document.adminForm;
		if (task == 'user.cancel' || document.formvalidator.isValid(form)) {
			p1 = $('jform_password').value;
			p2 = $('jform_password2').value;
			if ((p1 || p2) && p1 != p2) {
				alert('<?php echo JText::_('COM_ARTOFUSER_ERROR_PASSWORDS_DO_NOT_MATCH');?>');
			} else {
				submitform(task);
			}
		}
	}

	window.addEvent('domready', function() {
		$('jform_block0').addEvent('click', function(e){
			$('block-catid').setStyle('display', 'none');
		});
		$('jform_block1').addEvent('click', function(e){
			$('block-catid').setStyle('display', 'table-row');
		});
		if ($('jform_block0').checked == true) {
			$('jform_block0').fireEvent('click');
		} else {
			$('jform_block1').fireEvent('click');
		}
	});
-->
</script>

<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=users');?>" method="post" name="adminForm" id="user-form">
	<fieldset>
		<legend>
			<?php echo $this->item->id ? JText::sprintf('COM_ARTOFUSER_EDIT_USER_N', $this->item->id) : JText::_('COM_ARTOFUSER_NEW_USER');?>
		</legend>

		<div class="col width-40">
			<table class="admintable">
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['name']->label; ?>
					</td>
					<td>
						<?php echo $fields['name']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['username']->label; ?>
					</td>
					<td>
						<?php echo $fields['username']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['email']->label; ?>
					</td>
					<td>
						<?php echo $fields['email']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['password']->label; ?>
					</td>
					<td>
						<?php echo $fields['password']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['password2']->label; ?>
					</td>
					<td>
						<?php echo $fields['password2']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['block']->label; ?>
					</td>
					<td>
						<?php echo $fields['block']->input; ?>
					</td>
				</tr>
				<tr id="block-catid">
					<td scope="row" class="key">
						<?php echo $fields['block_catid']->label; ?>
					</td>
					<td>
						<?php echo $fields['block_catid']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['sendEmail']->label; ?>
					</td>
					<td>
						<?php echo $fields['sendEmail']->input; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo JText::_('COM_ARTOFUSER_USER_REGISTER_DATE'); ?>
					</td>
					<td>
						<?php echo $this->item->registerDate; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo JText::_('COM_ARTOFUSER_USER_LASTVISIT_DATE'); ?>
					</td>
					<td>
						<?php echo $this->item->lastvisitDate; ?>
					</td>
				</tr>
				<tr>
					<td scope="row" class="key">
						<?php echo $fields['note']->label; ?>
					</td>
					<td>
						<?php echo $fields['note']->input; ?>
					</td>
				</tr>
			</table>
		</div>
		<div class="col">
			<fieldset class="clearfix">
				<legend><?php echo JText::_('COM_ARTOFUSER_FIELD_PARAMS_LABEL'); ?></legend>

				<table>
				<?php foreach($this->form->getFields('params') as $field): ?>
					<?php if ($field->hidden): ?>
						<?php echo $field->input; ?>
					<?php else: ?>
						<tr>
							<td class="paramlist_key" width="40%">
								<?php echo $field->label; ?>
							</td>
							<td class="paramlist_value">
								<?php echo $field->input; ?>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</table>
			</fieldset>

			<fieldset class="clearfix">
				<legend><?php echo JText::_('COM_ARTOFUSER_USER_GROUPS'); ?></legend>

				<table class="admintable">
					<tr>
						<td scope="row" class="key">
							<?php echo $fields['gid']->label; ?>
						</td>
						<td>
							<?php echo $fields['gid']->input; ?>
						</td>
					</tr>
					<tr>
						<td scope="row" class="key">
							<?php echo $fields['groups']->label; ?>
						</td>
						<td>
							<?php echo $fields['groups']->input; ?>
						</td>
					</tr>
				</table>
			</fieldset>

			<?php foreach ($customFieldsets as $fieldset) : ?>
				<fieldset class="clearfix">
				<?php if (!empty($fieldset->legend)) : ?>
					<legend><?php echo $fieldset->legend; ?></legend>
				<?php endif; ?>
				<?php if (!empty($fieldset->content)) : ?>
					<?php echo $fieldset->content; ?>
				<?php endif; ?>
				</fieldset>
			<?php endforeach; ?>

		</div>

		<?php echo $fields['id']->input; ?>
		<input type="hidden" name="option" value="com_artofuser" />
		<input type="hidden" name="task" value="" />
	</fieldset>
	<?php echo JHTML::_('form.token'); ?>
</form>
<script type="text/javascript">
// Attach the onblur event to auto-create the alias
e = document.getElementById('jform_name');
e.onblur = function(){
	title = document.getElementById('jform_name');
	alias = document.getElementById('jform_value');
	if (alias.value=='') {
		alias.value = title.value.replace(/[\s\-]+/g,'-').replace(/&/g,'and').replace(/[^A-Z0-9\-\_]/ig,'').toLowerCase();
	}
}
</script>
