<?php
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// Create shortcut to parameters.
$params = $this->item->params;
$canEdit	= $this->item->params->get('access-edit');
?>
<div id="page" class="item-page<?php echo $params->get('pageclass_sfx')?>">

<?php if (($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')) && !($this->print)) : ?>
<div class="contentpaneopen_edit<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
	<?php echo JHTML::_('icon.edit', $this->item, $params); ?>
</div>
<?php endif; ?>

<?php if ($params->get('show_page_heading',1)) : ?>
<h1 class="componentheading<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
        <?php echo $this->escape($params->get('page_title')); ?>
</h1>
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
<h2 class="contentheading<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
	<?php if ($params->get('link_titles') && $this->item->readmore_link != '') : ?>
	<a href="<?php echo $this->item->readmore_link; ?>" class="contentpagetitle<?php echo $this->escape($params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->item->title); ?></a>
	<?php else :
		echo $this->escape($this->item->title);
	endif; ?>
</h2>
<?php endif; ?>

<?php if ($params->get('access-edit') ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
<p class="buttonheading">
	<?php if ($this->print) :
		echo JHTML::_('icon.print_screen', $this->item, $params);
	elseif ($params->get('show_pdf_icon') || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<?php if ($params->get('show_pdf_icon')) :
			echo JHTML::_('icon.pdf', $this->item, $params);
		endif;
		if ($params->get('show_print_icon')) :
			echo JHTML::_('icon.print_popup', $this->item, $params);
		endif;
		if ($params->get('show_email_icon')) :
			echo JHTML::_('icon.email', $this->item, $params);
		endif;
		if ($canEdit) :
			echo JHTML::_('icon.edit', $this->item, $params);
		endif;
	endif; ?>
</p>
<div class="clr"></div>
<?php endif; ?>

	<?php  if (!$params->get('show_intro')) :
		echo $this->item->event->afterDisplayTitle;
	endif; ?>


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
	<?php if ($params->get('show_parent_category') && $this->item->parent_slug != '1:root') : ?>
	<span class="parent-category-name">
		<?php $title = $this->escape($this->item->parent_title);
				$title = ($title) ? $title : JText::_('JGLOBAL_UNCATEGORISED');
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_parent_category') AND $this->item->parent_slug) : ?>
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
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
			<?php if ($params->get('link_category') AND $this->item->catslug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</span>
	<?php endif; ?>
</p>
<?php endif; ?>

 <?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset ($this->item->toc)) : ?>
		<?php echo $this->item->toc; ?>
	<?php endif; ?>

	<?php echo $this->item->text; ?>

	<?php echo $this->item->event->afterDisplayContent; ?>
</div>