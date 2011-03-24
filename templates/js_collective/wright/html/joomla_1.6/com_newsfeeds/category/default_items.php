<?php
// no direct access
defined('_JEXEC') or die;

JHtml::core();

$n = count($this->items);
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>

<?php if (empty($this->items)) : ?>
	<p> <?php echo JText::_('COM_NEWSFEEDS_NO_ARTICLES'); ?>	 </p>
<?php else : ?>

<?php if ( $this->params->get( 'show_limit' ) ) : ?>
<div class="display">
	<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
		<label for="limit"><?php echo JText::_( 'JGLOBAL_DISPLAY_NUM' ); ?>&nbsp;</label>
		<?php echo $this->pagination->getLimitBox(); ?>

		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</form>
</div>
<?php endif; ?>

	<table class="newsfeeds">
		<?php if ($this->params->get('show_headings')==1) : ?>
		<thead><tr class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>">
				<?php if ($this->params->get('show_name')) : ?>
				<th class="item-title" id="tableOrdering">
					<?php echo JHtml::_('grid.sort',  JText::_('COM_NEWSFEEDS_FEED_NAME'), 'a.name', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_articles')) : ?>
				<th width="90%" class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>" id="tableOrdering2">
					<?php echo JHtml::_('grid.sort',  JText::_('COM_NEWSFEEDS_NUM_ARTICLES'), 'a.numarticles', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>

				<?php if ($this->params->get('show_link')) : ?>
				<th width="10%" class="sectiontableheader<?php echo $this->escape($this->params->get( 'pageclass_sfx' )); ?>" id="tableOrdering3">
					<?php echo JHtml::_('grid.sort',  JText::_('COM_NEWSFEEDS_FEED_LINK'), 'a.link', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="sectiontableentry<?php echo $i % 2 ? '1' : '2';?>">

					<td class="item-title">
						<a href="<?php echo JRoute::_(NewsFeedsHelperRoute::getNewsfeedRoute($item->id, $item->catid)); ?>">
							<?php echo $item->name; ?></a>
					</td>

					<?php  if ($this->params->get('show_articles')) : ?>
						<td class="item-num-art">
							<?php echo $item->numarticles; ?>
						</td>
					<?php  endif; ?>

					<?php  if ($this->params->get('show_link')) : ?>
						<td class="item-link">
							<a href="<?php echo $item->link; ?>"><?php echo $item->link; ?></a>
						</td>
					<?php  endif; ?>

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

<?php endif; ?>