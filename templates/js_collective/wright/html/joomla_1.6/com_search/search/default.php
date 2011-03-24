<?php
// no direct access
defined('_JEXEC') or die;
?>

<div class="search<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')) ?>">
	<?php if ($this->escape($this->params->get('page_heading'))) :?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
</h1>
<?php endif; ?>

<?php include('default_form.php'); ?>
<?php if ($this->error==null && count($this->results) > 0) :
	include('default_results.php');
else :
	include('default_error.php');
endif; ?>
</div>
