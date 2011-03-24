<?php

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$canEdit = $this->user->authorise('core.edit', 'com_content.category.' . $this->item->id);
?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
	<h2 class="contentheading<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
		<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
	</h2>
<?php endif; ?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
	<p class="buttonheading">
		<?php if ($params->get('show_print_icon')) : ?>
			<?php echo JHtml::_('icon.print_popup', $this->item, $params); ?>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
			<?php echo JHtml::_('icon.email', $this->item, $params); ?>
		<?php endif; ?>
		<?php if ($canEdit) : ?>
			<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
		<?php endif; ?>
	</p>
<?php endif; ?>

<?php if (!$params->get('show_intro')) : ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>
<?php endif; ?>

<?php // to do not that elegant would be nice to group the params ?>

<?php $useDefList = (($params->get('show_author')) OR ($params->get('show_category')) OR ($params->get('show_parent_category'))
	OR ($params->get('show_create_date')) OR ($params->get('show_modify_date')) OR ($params->get('show_publish_date'))
	OR ($params->get('show_hits'))); ?>

<?php if ($useDefList) : ?>
<p class="iteminfo">
	<?php if ($params->get('show_create_date')) : ?>
		<span class="createdate">
		<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHTML::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
		</span>
	<?php endif; ?>
	<?php if ($params->get('show_modify_date')) : ?>
		<span class="modifydate">
		<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
		</span>
	<?php endif; ?>
	<?php if ($params->get('show_publish_date')) : ?>
		<span class="publishdate">
		<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE', JHTML::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
		</span>
	<?php endif; ?>
	<?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
		<span class="createdby">
		<?php $author = $params->get('link_author', 0) ? JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&member_id='.$this->item->created_by),$this->item->author) : $this->item->author; ?>
		<?php $author=($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>
	<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
		</span>
	<?php endif; ?>
	<?php if ($params->get('show_hits')) : ?>
		<span class="hits">
		<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
		</span>
	<?php endif; ?>
</p>
<div class="clr"></div>

<p class="iteminfo">
	<?php if ($params->get('show_parent_category')) : ?>
	<span class="parent-category-name">
		<?php $title = $this->escape($this->item->parent_title);
				$title = ($title) ? $title : JText::_('JGLOBAL_UNCATEGORISED');
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_id)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_parent_category')) : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
			<?php endif; ?>
	</span>
	<?php endif; ?>
	<?php if ($params->get('show_category')) : ?>
		<span class="category-name">
			<?php 	$title = $this->escape($this->item->category_title);
					$title = ($title) ? $title : JText::_('JGLOBAL_UNCATEGORISED');
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catid)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_category')) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
</p>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php echo $this->item->introtext; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
		$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug));
		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
?>
		<p class="readmore">
				<a href="<?php echo $link; ?>">
					<?php if (!$params->get('access-view')) :
						echo JText::_('REGISTER_TO_READ_MORE');
					elseif ($readmore = $this->item->alternative_readmore) :
						echo $readmore;
					else :
						echo JText::sprintf('COM_CONTENT_READ_MORE', $this->escape((strlen($this->item->title) > 40) ? substr($this->item->title, 0, 40).'...' : $this->item->title));
					endif; ?></a>
		</p>
<?php endif; ?>

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
