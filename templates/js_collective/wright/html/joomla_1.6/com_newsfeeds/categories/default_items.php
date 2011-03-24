<?php
// no direct access
defined('_JEXEC') or die;
$class = ' class="first"';
if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
<ul>
<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php
	if($this->params->get('show_empty_categories') || $item->numitems || count($item->getChildren())) :
	if(!isset($this->items[$this->parent->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li<?php echo $class; ?>>
	<?php $class = ''; ?>
		<a href="<?php echo JRoute::_(NewsfeedsHelperRoute::getCategoryRoute($item->id));?>" class="category">
			<?php echo $this->escape($item->title); ?></a>
		<?php if ($this->params->get('show_item_count') == 1) :?>
			<span class="small"><?php echo JText::_('COM_NEWSFEED_COUNT:'); ?> <?php echo $item->numitems; ?>
			</span>
		<?php endif; ?>
		<?php if ($item->description) : ?>
			<br />
			<?php echo JHtml::_('content.prepare', $item->description); ?>
		<?php endif; ?>

		<?php if(count($item->getChildren()) > 0) :
			$this->items[$item->id] = $item->getChildren();
			$this->parent = $item;
			$this->maxLevel--;
			include('default_items.php');
			$this->parent = $item->getParent();
			$this->maxLevel++;
		endif; ?>

	</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>