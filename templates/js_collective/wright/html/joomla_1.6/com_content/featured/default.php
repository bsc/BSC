<?php

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
$pageClass = $this->params->get('pageclass_sfx');
?>

<?php if ( $this->params->get('show_page_heading')!=0) : ?>
	<h1 class="componentheading<?php echo $pageClass ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<div class="blog<?php echo $pageClass;?>">

<?php $leadingcount=0 ;  ?>
<?php if (!empty($this->lead_items)) : ?>
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading<?php echo $pageClass; ?>">
			<?php
				$this->item = &$item;
				include('default_item.php');
			?>
			<span class="leading_separator<?php echo $pageClass; ?>">&nbsp;</span>
		</div>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
<?php endif; ?>

<?php $introcount = count($this->intro_items);
if ($introcount) :
	$colcount = (int) $this->columns;
	$rowcount = (int) $introcount / $colcount;
	$ii = 0;
	$i = $leadingcount;
	for ($y = 0; $y < $rowcount && $i < $introcount + $leadingcount; $y++) : ?>
		<div class="article_row<?php echo $pageClass; ?>">
			<?php for ($z = 0; $z < $colcount && $ii < $introcount && $i <= $introcount && isset($this->intro_items[$i]); $z++, $i++, $ii++) : ?>
				<div class="article_column column<?php echo $z + 1; ?> cols<?php echo $colcount; ?>" >
					<?php $this->item =& $this->intro_items[$i];
					include(dirname(__FILE__).DS.'default_item.php') ?>
				</div>
				<span class="article_separator">&nbsp;</span>
			<?php endfor; ?>
			<span class="row_separator<?php echo $pageClass; ?>">&nbsp;</span>
		</div>
	<?php endfor;
endif; ?>

<?php if (!empty($this->link_items)) : ?>

	<?php include('default_links.php'); ?>

<?php endif; ?>

	<div class="clear"></div>

<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php  endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>

</div>