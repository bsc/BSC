<?php

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

$pageClass = $this->params->get('pageclass_sfx');
?>

<?php if ($this->params->get('show_page_heading')!=0 or $this->params->get('show_category_title')): ?>
<h1 class="componentheading<?php echo $pageClass; ?>">

<?php if ( $this->params->get('show_page_heading')!=0) : ?>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
<?php endif; ?>
	<?php if ($this->params->get('show_category_title')) :?>


	<?php	echo '<span class="subheading-category">'.$this->category->title.'</span>'; ?>
	<?php endif; ?>

</h1>
<?php endif; ?>

<div class="blog<?php echo $pageClass;?>">


<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="contentdescription<?php echo $pageClass ?>">
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	<?php endif; ?>
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
		<?php echo JHtml::_('content.prepare', $this->category->description); ?>
	<?php endif; ?>
	<div class="clr"></div>
	</div>
<?php endif; ?>

<?php $leadingcount=0; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="leading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? 'class="system-unpublished"' : null; ?>">
			<?php
				$this->item = &$item;
				include(dirname(__FILE__).DS.'blog_item.php');
			?>
		</div>
		<span class="leading_separator<?php echo $pageClass; ?>">&nbsp;</span>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php $introcount = (count($this->intro_items));
if ($introcount) :
	$colcount = (int) $this->columns;
	$rowcount = (int) $introcount / $colcount;
	$ii = 0;
	$i = $leadingcount;
	for ($y = 0; $y < $rowcount && $i < $introcount; $y++) : ?>
		<div class="article_row<?php echo $pageClass; ?>">
			<?php for ($z = 0; $z < $colcount && $ii < $introcount && $i <= $introcount && isset($this->intro_items[$i]); $z++, $i++, $ii++) : ?>
				<div class="article_column column<?php echo $z + 1; ?> cols<?php echo $colcount; ?>" >
					<?php $this->item =& $this->intro_items[$i];
					include(dirname(__FILE__).DS.'blog_item.php') ?>
				</div>
				<span class="article_separator">&nbsp;</span>
			<?php endfor; ?>
			<span class="row_separator<?php echo $pageClass; ?>">&nbsp;</span>
		</div>
	<?php endfor;
endif; ?>

<?php if (!empty($this->link_items)) : ?>

	<?php include('blog_links.php'); ?>

<?php endif; ?>
	<div class="clear"></div>

	<?php if (is_array($this->children[$this->category->id]) && count($this->children[$this->category->id]) > 0 && $this->params->get('maxLevel') !=0) : ?>
		<div class="categories">
		<h2 class="contentheading<?php echo $pageClass; ?>">
			<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
		</h2>
			<?php echo include('blog_children.php'); ?>
		</div>
	<?php endif; ?>

<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
		</p>

		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php  endif; ?>

</div>
