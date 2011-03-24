<?php
defined('_JEXEC') or die;

JHtml::_('behavior.mootools');
JHtml::_('behavior.formvalidation');
?>

<?php if ($this->params->get('show_page_heading')) : ?>
	<h1 class="componentheading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<div class="reset-complete<?php echo $this->params->get('pageclass_sfx')?>">
	

	<form action="<?php echo JRoute::_('index.php?option=com_users&task=reset.complete'); ?>" method="post" class="josForm form-validate">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
		<tr>
			<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
			<td height="40"><label for="<?php echo $name ?>" class="hasTip"><?php echo $field->label; ?></label></td>
			<td><?php echo str_replace('class="', 'class="inputbox ', $field->input); ?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>

		<button type="submit"><?php echo JText::_('JSUBMIT'); ?></button>
		<?php echo JHtml::_('form.token'); ?>
		</table>
	</form>
</div>