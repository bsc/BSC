<?php
// no direct access
defined('_JEXEC') or die;
JHtml::core();
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

// Create a shortcut for params.
$params = &$this->item->params;
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_CONTACT_NO_CONTACTS'); ?>	 </p>
<?php else : ?>

<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
	<fieldset class="filters">
	<legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
	<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>
	</fieldset>

	<table class="category">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead><tr>
			<th class="item-num">
				<?php echo JText::_('Num'); ?>
			</th>
			<th class="item-title">
				<?php echo JHtml::_('grid.sort',  'Name', 'a.name', $listDirn, $listOrder); ?>
			</th>
			<?php if ($this->params->get('show_position')) : ?>
			<th class="item-position">
				<?php echo JHtml::_('grid.sort',  'Position', 'a.con_position', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_email')) : ?>
			<th class="item-email">
				<?php echo JText::_('Email'); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_telephone')) : ?>
			<th class="item-phone">
				<?php echo JText::_('Phone'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_mobile')) : ?>
			<th class="item-phone">
				<?php echo JText::_('Mobile'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_fax')) : ?>
			<th class="item-phone">
				<?php echo JText::_('Fax'); ?>
			</th>
			<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach($this->items as $i => $item) : ?>
				<tr class="<?php echo ($i % 2) ? "odd" : "even"; ?>">
					<td class="item-num">
						<?php echo $i; ?>
					</td>

					<td class="item-title">
						<a href="<?php echo JRoute::_(ContactHelperRoute::getContactRoute($item->slug, $item->catid)); ?>">
							<?php echo $item->name; ?></a>
					</td>

					<?php if ($this->params->get('show_position')) : ?>
						<td class="item-position">
							<?php echo $item->con_position; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_email')) : ?>
						<td class="item-email">
							<?php echo $item->email_to; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_telephone')) : ?>
						<td class="item-phone">
							<?php echo $item->telephone; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile')) : ?>
						<td class="item-phone">
							<?php echo $item->mobile; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_fax')) : ?>
					<td class="item-phone">
						<?php echo $item->fax; ?>
					</td>
					<?php endif; ?>

				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

	<?php if ($this->params->get('show_pagination')) : ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</div>
</form>
<?php endif; ?>

<div class="item-separator"></div>
