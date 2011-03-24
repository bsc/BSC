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
?>
<script language="javascript" type="text/javascript">
<!--
	function submitbutton(task)
	{
		var form = document.adminForm;
		if (task == 'cancel' || document.formvalidator.isValid(document.adminForm)) {
			submitform(task);
		}
	}
-->
</script>
<form action="<?php echo JRoute::_('index.php?option=com_artofuser&view=groups');?>" method="post" name="adminForm" id="group-form">
	<fieldset>
		<legend><?php echo $this->item->id ? JText::sprintf('COM_ARTOFUSER_EDIT_GROUP_N', $this->item->id) : JText::_('COM_ARTOFUSER_NEW_GROUP');?></legend>

		<table class="admintable">
			<tr>
				<td scope="row" class="key">
					<?php echo $fields['parent_id']->label; ?>
				</td>
				<td>
					<?php echo $fields['parent_id']->input; ?>
				</td>
			</tr>
			<tr>
				<td scope="row" class="key">
					<?php echo $fields['name']->label; ?>
				</td>
				<td>
					<?php echo $fields['name']->input; ?>
				</td>
			</tr>
		</table>

		<?php echo $fields['id']->input; ?>
		<?php echo $fields['value']->input; ?>
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
