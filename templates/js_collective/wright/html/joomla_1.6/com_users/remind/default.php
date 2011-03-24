<?php
defined('_JEXEC') or die;

JHtml::_('behavior.mootools');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<div class="remind<?php echo $this->params->get('pageclass_sfx')?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h2 class="componentheading">
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h2>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=remind.remind'); ?>" method="post" class="form-validate">

		<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
			<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
				<?php echo $field->label; ?>
				<?php echo str_replace('class="', 'class="inputbox ', $field->input);; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>

		<button type="submit"><?php echo JText::_('JSUBMIT'); ?></button>

		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>